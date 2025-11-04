@extends('layouts.app')

@section('title', 'Log History')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div>
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Riwayat Aktivitas Log</h1>
        <p class="text-gray-500">Lacak semua perubahan dan aktivitas admin</p>
    </div>

    <!-- Controls -->
    <div class="flex items-center gap-4">
        <!-- Search -->
        <div class="flex-1 max-w-md relative">
            <i class="fas fa-search absolute left-3 top-1/3 -translate-y-1/2 text-gray-400"></i>
            <input type="text" 
                   placeholder="Cari berdasarkan admin atau aksi..." 
                   class="pl-10 h-10 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                   id="searchInput">
        </div>
        
        <!-- Date Filter -->
        <button class="border border-gray-300 hover:bg-gray-50 text-gray-700 px-4 py-2 rounded-lg font-medium transition-colors flex items-center">
            <i class="fas fa-calendar mr-2"></i>
            Filter Tanggal
        </button>
    </div>

    <!-- Logs Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Admin</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Detail</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($logs as $log)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500">{{ $log->created_at->format('d M Y, H:i') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-blue-600">{{ $log->user->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $badgeColors = [
                                    'LOGIN' => 'bg-blue-100 text-blue-600',
                                    'LOGOUT' => 'bg-gray-100 text-gray-600',
                                    'PRODUCT_CREATE' => 'bg-green-100 text-green-600',
                                    'PRODUCT_UPDATE' => 'bg-yellow-100 text-yellow-600',
                                    'PRODUCT_DELETE' => 'bg-red-100 text-red-600',
                                    'STOCK_UPDATE' => 'bg-purple-100 text-purple-600',
                                    'REPORT_DOWNLOAD' => 'bg-indigo-100 text-indigo-600',
                                ];
                                $badgeColor = $badgeColors[$log->action] ?? 'bg-gray-100 text-gray-600';
                                $actionLabels = [
                                    'LOGIN' => 'LOGIN',
                                    'LOGOUT' => 'LOGOUT',
                                    'PRODUCT_CREATE' => 'CREATE',
                                    'PRODUCT_UPDATE' => 'UPDATE',
                                    'PRODUCT_DELETE' => 'DELETE',
                                    'STOCK_UPDATE' => 'STOCK',
                                    'REPORT_DOWNLOAD' => 'REPORT',
                                ];
                                $actionLabel = $actionLabels[$log->action] ?? $log->action;
                            @endphp
                            <span class="px-2 py-1 rounded-full text-xs font-medium {{ $badgeColor }}">
                                {{ $actionLabel }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-700">{{ $log->description }}</div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="bg-white px-6 py-4 border-t border-gray-200">
            {{ $logs->links() }}
        </div>
    </div>

    <!-- Results Count -->
    <div class="text-center text-gray-500 text-sm">
        Menampilkan {{ $logs->count() }} dari {{ $logs->total() }} aktivitas
    </div>
</div>

<script>
// Search functionality
document.getElementById('searchInput').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const rows = document.querySelectorAll('tbody tr');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
    });
});
</script>
@endsection