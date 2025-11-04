@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Page Title -->
    <div>
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Dashboard</h1>
        <p class="text-gray-500">Ringkasan kondisi inventaris Anda</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Products -->
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-gray-500 mb-2">Total Produk</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalProducts }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-box text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Stock -->
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-gray-500 mb-2">Stok Tersedia</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($totalStock) }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-chart-line text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Out of Stock -->
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-gray-500 mb-2">Stok Habis</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $outOfStock }}</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Value -->
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-gray-500 mb-2">Nilai Total Stok</p>
                    <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($totalValue, 0, ',', '.') }}</p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-shopping-cart text-orange-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Lower Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Low Stock Products -->
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 lg:col-span-1">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-gray-900">Produk Akan Habis</h3>
                <span class="bg-orange-100 text-orange-600 px-2 py-1 rounded-full text-sm font-medium">
                    {{ $lowStockProducts->count() }}
                </span>
            </div>
            <div class="space-y-3">
                @foreach($lowStockProducts as $product)
                <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-0">
                    <span class="text-gray-700 text-sm">{{ $product->name }}</span>
                    <span class="text-orange-600 font-medium">{{ $product->stock }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 lg:col-span-2">
            <h3 class="font-semibold text-gray-900 mb-4">Aktivitas Terbaru</h3>
            <div class="space-y-4">
                @foreach($recentActivities as $activity)
                <div class="flex items-start gap-3">
                    <div class="w-2 h-2 bg-blue-600 rounded-full mt-2"></div>
                    <div class="flex-1">
                        <p class="text-gray-700 text-sm">
                            <span class="text-blue-600">{{ $activity->user->name }}</span>:
                            {{ $activity->description }}
                        </p>
                        <p class="text-gray-400 text-xs mt-1">{{ $activity->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
        <h3 class="font-semibold text-gray-900 mb-4">Quick Actions</h3>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('products.index') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center">
                <i class="fas fa-plus mr-2"></i>
                Tambah Produk
            </a>
            <!-- Download Laporan PDF -->
            <a href="{{ route('reports.stock.pdf') }}" 
            class="border border-gray-300 hover:bg-gray-50 text-gray-700 px-4 py-2 rounded-lg font-medium transition-colors flex items-center">
                <i class="fas fa-download mr-2"></i>
                Download Laporan Stok
            </a>
            <a href="{{ route('activity-logs.index') }}" 
               class="border border-gray-300 hover:bg-gray-50 text-gray-700 px-4 py-2 rounded-lg font-medium transition-colors flex items-center">
                <i class="fas fa-history mr-2"></i>
                Lihat Log
            </a>
        </div>
    </div>
</div>
@endsection