<header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6 sticky top-0 z-50">
    <!-- Search -->
    <div class="flex-1 max-w-md relative" x-data="{
        open: false,
        query: '',
        results: [],
        search() {
            if (this.query.length < 2) {
                this.results = [];
                this.open = false;
                return;
            }
            
            // Simulasi search results - bisa diganti dengan AJAX call
            const allPages = [
                { name: 'Dashboard', url: '{{ route('dashboard') }}', icon: 'fas fa-tachometer-alt', category: 'Main' },
                { name: 'Manajemen Stok', url: '{{ route('products.index') }}', icon: 'fas fa-boxes', category: 'Management' },
                { name: 'Log History', url: '{{ route('activity-logs.index') }}', icon: 'fas fa-history', category: 'Reports' },
                { name: 'Tambah Produk', url: '{{ route('products.index') }}', icon: 'fas fa-plus', category: 'Actions' },
                { name: 'Download Laporan', url: '{{ route('reports.stock.pdf') }}', icon: 'fas fa-download', category: 'Reports' },
            ];
            
            this.results = allPages.filter(page => 
                page.name.toLowerCase().includes(this.query.toLowerCase()) ||
                page.category.toLowerCase().includes(this.query.toLowerCase())
            );
            this.open = this.results.length > 0;
        },
        navigate(url) {
            window.location.href = url;
            this.open = false;
            this.query = '';
        }
    }" @click.away="open = false">
        <div class="relative">
            <i class="fas fa-search absolute left-3 top-1/3 -translate-y-1/2 text-gray-400"></i>
            <input type="text" 
                   x-model="query"
                   @input="search()"
                   @focus="query.length >= 2 && results.length > 0 ? open = true : open = false"
                   placeholder="Cari halaman atau menu..." 
                   class="pl-10 h-10 bg-gray-50 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors">
        </div>

        <!-- Search Results Dropdown -->
        <div x-show="open" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             class="absolute top-12 left-0 right-0 bg-white rounded-lg shadow-lg border border-gray-200 max-h-96 overflow-y-auto z-50">
            
            <!-- Results Header -->
            <div class="px-4 py-2 border-b border-gray-100 bg-gray-50">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-700">Hasil Pencarian</span>
                    <span class="text-xs text-gray-500" x-text="results.length + ' hasil'"></span>
                </div>
            </div>

            <!-- Search Results -->
            <template x-if="results.length > 0">
                <div class="py-2">
                    <template x-for="result in results" :key="result.name">
                        <button @click="navigate(result.url)" 
                                class="w-full text-left px-4 py-3 hover:bg-blue-50 transition-colors flex items-start gap-3 group">
                            <div class="flex-shrink-0 w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                                <i :class="result.icon + ' text-blue-600 text-sm'"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-medium text-gray-900" x-text="result.name"></div>
                                <div class="text-xs text-gray-500 mt-1" x-text="result.category"></div>
                            </div>
                            <i class="fas fa-chevron-right text-gray-400 text-xs mt-2 group-hover:text-blue-600 transition-colors"></i>
                        </button>
                    </template>
                </div>
            </template>

            <!-- No Results -->
            <template x-if="query.length >= 2 && results.length === 0">
                <div class="px-4 py-8 text-center">
                    <i class="fas fa-search text-gray-300 text-2xl mb-2"></i>
                    <p class="text-gray-500 text-sm">Tidak ditemukan hasil untuk "<span x-text="query" class="font-medium"></span>"</p>
                    <p class="text-gray-400 text-xs mt-1">Coba dengan kata kunci lain</p>
                </div>
            </template>

            <!-- Search Tips -->
            <div class="px-4 py-3 border-t border-gray-100 bg-gray-50">
                <div class="text-xs text-gray-500">
                    <span class="font-medium">Tips:</span> Coba "stok", "laporan", "produk"
                </div>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="flex items-center gap-4">
        <!-- Notifications -->
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" class="relative p-2 hover:bg-gray-100 rounded-lg transition-colors">
                <i class="fas fa-bell text-gray-600"></i>
                @if($unreadCount > 0)
                    <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                @endif
            </button>

            <!-- Notifications Dropdown -->
            <div x-show="open" 
                @click.away="open = false" 
                class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50">
                <div class="px-4 py-2 border-b border-gray-100 flex items-center justify-between">
                    <span class="font-medium text-gray-900">Notifikasi</span>
                    @if(count($notifications) > 0)
                        <span class="bg-blue-100 text-blue-600 px-2 py-1 rounded-full text-xs font-medium">
                            {{ count($notifications) }} reminder
                        </span>
                    @endif
                </div>
                
                <!-- Notification Items -->
                <div class="max-h-60 overflow-y-auto">
                    @forelse($notifications as $notification)
                        <a href="{{ $notification['url'] }}" 
                        class="flex items-start gap-3 px-4 py-3 hover:bg-gray-50 transition-colors border-b border-gray-100 cursor-pointer block">
                            <!-- Notification Icon -->
                            <div class="flex-shrink-0 w-8 h-8 rounded-full {{ str_replace('text', 'bg', $notification['color']) }} bg-opacity-10 flex items-center justify-center">
                                <i class="{{ $notification['icon'] }} {{ $notification['color'] }} text-sm"></i>
                            </div>
                            
                            <!-- Notification Content -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between">
                                    <p class="text-sm font-medium text-gray-900">{{ $notification['title'] }}</p>
                                    <span class="text-xs text-gray-400 ml-2 whitespace-nowrap">{{ $notification['time'] }}</span>
                                </div>
                                <p class="text-sm text-gray-600 mt-1">{{ $notification['message'] }}</p>
                            </div>
                            
                            <!-- Arrow Icon -->
                            <div class="flex-shrink-0 mt-1">
                                <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
                            </div>
                        </a>
                    @empty
                        <!-- Empty State -->
                        <div class="px-4 py-8 text-center">
                            <i class="fas fa-bell-slash text-gray-300 text-2xl mb-2"></i>
                            <p class="text-gray-500 text-sm">Tidak ada notifikasi</p>
                            <p class="text-gray-400 text-xs mt-1">Semua berjalan dengan baik!</p>
                        </div>
                    @endforelse
                </div>
                
                @if(count($notifications) > 0)
                    <div class="px-4 py-2 border-t border-gray-100 text-center">
                        <span class="text-xs text-gray-500">Klik notifikasi untuk melihat di Manajemen Stok</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Profile -->
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" class="flex items-center gap-3 hover:bg-gray-100 rounded-lg p-2 transition-colors">
                <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                    <i class="fas fa-user text-white text-sm"></i>
                </div>
                <span class="text-gray-700">{{ auth()->user()->name }}</span>
            </button>

            <!-- Dropdown Menu -->
            <div x-show="open" 
                 @click.away="open = false" 
                 class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50">
                <div class="px-4 py-2 border-b border-gray-100">
                    <p class="font-medium">{{ auth()->user()->name }}</p>
                    <p class="text-gray-500 text-sm">{{ auth()->user()->email }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 text-red-600 hover:bg-gray-50 flex items-center">
                        <i class="fas fa-sign-out-alt mr-2"></i>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>