<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\StockHistory;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');
        
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
        }

        $products = $query->latest()->paginate(10);
        $categories = Category::all();

        return view('stock-management', compact('products', 'categories'));
    }

    public function store(Request $request)
    {
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

        $product = Product::create($validated);
        $product->updateStatus();

        // Log stock history
        if ($validated['stock'] > 0) {
            StockHistory::create([
                'product_id' => $product->id,
                'user_id' => auth()->id(),
                'type' => 'initial',
                'quantity' => $validated['stock'],
                'old_stock' => 0,
                'new_stock' => $validated['stock'],
                'note' => 'Stok awal'
            ]);
        }

        ActivityLog::log(
            auth()->id(),
            'PRODUCT_CREATE',
            "Admin menambah produk baru '{$product->name}'"
        );

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil ditambahkan!');
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products,sku,' . $product->id,
            'category_id' => 'required|exists:categories,id',
            'buy_price' => 'required|numeric|min:0',
            'sell_price' => 'required|numeric|min:0',
            'min_stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
        ]);

        $product->update($validated);
        $product->updateStatus();

        ActivityLog::log(
            auth()->id(),
            'PRODUCT_UPDATE',
            "Admin memperbarui produk '{$product->name}'"
        );

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy(Product $product)
    {
        $productName = $product->name;
        $product->delete();

        ActivityLog::log(
            auth()->id(),
            'PRODUCT_DELETE',
            "Admin menghapus produk '{$productName}'"
        );

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil dihapus!');
    }

    public function manageStock(Request $request, Product $product)
    {
        $validated = $request->validate([
            'action_type' => 'required|in:in,out',
            'quantity' => 'required|integer|min:1',
            'note' => 'nullable|string'
        ]);

        $oldStock = $product->stock;
        
        if ($validated['action_type'] === 'in') {
            // Tambah stok
            $product->increment('stock', $validated['quantity']);
            $message = "Stok berhasil ditambahkan!";
            $action = "STOCK_IN";
        } else {
            // Kurangi stok dengan validasi
            if ($validated['quantity'] > $oldStock) {
                return back()->with('error', 'Jumlah pengurangan melebihi stok yang tersedia!');
            }
            
            $product->decrement('stock', $validated['quantity']);
            $message = "Stok berhasil dikurangi!";
            $action = "STOCK_OUT";
        }

        $product->updateStatus();

        // Log stock history
        StockHistory::create([
            'product_id' => $product->id,
            'user_id' => auth()->id(),
            'type' => $validated['action_type'],
            'quantity' => $validated['quantity'],
            'old_stock' => $oldStock,
            'new_stock' => $product->stock,
            'note' => $validated['note']
        ]);

        ActivityLog::log(
            auth()->id(),
            $action,
            "Admin " . ($validated['action_type'] === 'in' ? 'menambah' : 'mengurangi') . " stok '{$product->name}' sebanyak {$validated['quantity']} unit"
        );

        return back()->with('success', $message);
    }
}