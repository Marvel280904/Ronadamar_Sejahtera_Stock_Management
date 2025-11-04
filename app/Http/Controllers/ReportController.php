<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use PDF;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function stockPdf()
    {
        $products = Product::with('category')
            ->orderBy('category_id')
            ->orderBy('name')
            ->get();
            
        $categories = Category::withCount('products')->get();
        $totalProducts = $products->count();
        $totalStock = $products->sum('stock');
        $totalValue = $products->sum(function($product) {
            return $product->stock * $product->sell_price;
        });

        $data = [
            'products' => $products,
            'categories' => $categories,
            'totalProducts' => $totalProducts,
            'totalStock' => $totalStock,
            'totalValue' => $totalValue,
            'reportDate' => Carbon::now()->format('d F Y'),
            'companyName' => 'Ronadamar Sejahtera',
        ];

        $pdf = PDF::loadView('reports.stock-pdf', $data);
        
        return $pdf->download('laporan-stok-' . Carbon::now()->format('Y-m-d') . '.pdf');
    }
}