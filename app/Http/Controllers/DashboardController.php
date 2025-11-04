<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();
        $totalStock = Product::sum('stock');
        $totalValue = Product::sum(\DB::raw('stock * sell_price'));
        $outOfStock = Product::where('stock', 0)->count();
        
        $lowStockProducts = Product::where('stock', '>', 0)
            ->where('stock', '<=', \DB::raw('min_stock'))
            ->orderBy('stock', 'asc')
            ->limit(5)
            ->get();

        $recentActivities = ActivityLog::with('user')
            ->latest()
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'totalProducts',
            'totalStock',
            'totalValue',
            'outOfStock',
            'lowStockProducts',
            'recentActivities'
        ));
    }
}