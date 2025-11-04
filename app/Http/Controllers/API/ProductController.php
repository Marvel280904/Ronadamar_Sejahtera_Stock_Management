<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\StockHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Get all products
     */
    public function index(Request $request)
    {
        try {
            $query = Product::with('category');
            
            // Search filter
            if ($request->has('search') && $request->search != '') {
                $search = $request->search;
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('sku', 'like', "%{$search}%");
            }
            
            // Category filter
            if ($request->has('category_id') && $request->category_id != '') {
                $query->where('category_id', $request->category_id);
            }
            
            // Status filter
            if ($request->has('status') && $request->status != '') {
                $query->where('status', $request->status);
            }
            
            $products = $query->orderBy('name')->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'products' => $products,
                    'total' => $products->count()
                ],
                'message' => 'Products retrieved successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve products',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get single product
     */
    public function show($id)
    {
        try {
            $product = Product::with('category')->find($id);

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'product' => $product
                ],
                'message' => 'Product retrieved successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve product',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create new product
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'sku' => 'required|string|unique:products',
                'category_id' => 'required|exists:categories,id',
                'buy_price' => 'required|numeric|min:0',
                'sell_price' => 'required|numeric|min:0',
                'stock' => 'required|integer|min:0',
                'min_stock' => 'required|integer|min:0',
                'description' => 'nullable|string',
            ]);

            DB::beginTransaction();

            $product = Product::create($validated);
            $product->updateStatus();

            // Log stock history
            if ($validated['stock'] > 0) {
                StockHistory::create([
                    'product_id' => $product->id,
                    'user_id' => $request->user()->id,
                    'type' => 'initial',
                    'quantity' => $validated['stock'],
                    'old_stock' => 0,
                    'new_stock' => $validated['stock'],
                    'note' => 'Stok awal via API'
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => [
                    'product' => $product->load('category')
                ],
                'message' => 'Product created successfully'
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create product',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update product
     */
    public function update(Request $request, $id)
    {
        try {
            $product = Product::find($id);

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found'
                ], 404);
            }

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'sku' => 'required|string|unique:products,sku,' . $id,
                'category_id' => 'required|exists:categories,id',
                'buy_price' => 'required|numeric|min:0',
                'sell_price' => 'required|numeric|min:0',
                'min_stock' => 'required|integer|min:0',
                'description' => 'nullable|string',
            ]);

            $product->update($validated);
            $product->updateStatus();

            return response()->json([
                'success' => true,
                'data' => [
                    'product' => $product->load('category')
                ],
                'message' => 'Product updated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update product',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete product
     */
    public function destroy($id)
    {
        try {
            $product = Product::find($id);

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found'
                ], 404);
            }

            $product->delete();

            return response()->json([
                'success' => true,
                'message' => 'Product deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete product',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add stock to product
     */
    public function addStock(Request $request, $id)
    {
        try {
            $product = Product::find($id);

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found'
                ], 404);
            }

            $validated = $request->validate([
                'quantity' => 'required|integer|min:1',
                'note' => 'nullable|string'
            ]);

            DB::beginTransaction();

            $oldStock = $product->stock;
            $product->increment('stock', $validated['quantity']);
            $product->updateStatus();

            StockHistory::create([
                'product_id' => $product->id,
                'user_id' => $request->user()->id,
                'type' => 'in',
                'quantity' => $validated['quantity'],
                'old_stock' => $oldStock,
                'new_stock' => $product->stock,
                'note' => $validated['note'] ?? 'Tambah stok via API'
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => [
                    'product' => $product->load('category')
                ],
                'message' => 'Stock added successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to add stock',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reduce stock from product
     */
    public function reduceStock(Request $request, $id)
    {
        try {
            $product = Product::find($id);

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found'
                ], 404);
            }

            $validated = $request->validate([
                'quantity' => 'required|integer|min:1',
                'note' => 'nullable|string'
            ]);

            // Check if enough stock
            if ($validated['quantity'] > $product->stock) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient stock. Available: ' . $product->stock
                ], 400);
            }

            DB::beginTransaction();

            $oldStock = $product->stock;
            $product->decrement('stock', $validated['quantity']);
            $product->updateStatus();

            StockHistory::create([
                'product_id' => $product->id,
                'user_id' => $request->user()->id,
                'type' => 'out',
                'quantity' => $validated['quantity'],
                'old_stock' => $oldStock,
                'new_stock' => $product->stock,
                'note' => $validated['note'] ?? 'Kurangi stok via API'
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => [
                    'product' => $product->load('category')
                ],
                'message' => 'Stock reduced successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to reduce stock',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all categories
     */
    public function categories()
    {
        try {
            $categories = Category::orderBy('name')->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'categories' => $categories
                ],
                'message' => 'Categories retrieved successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve categories',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}