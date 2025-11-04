<aside class="w-64 bg-white border-r border-gray-200 flex flex-col">
    <!-- Logo -->
    <div class="h-16 flex items-center px-6 border-b border-gray-200">
        <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center mr-3">
            <i class="fas fa-box text-white text-sm"></i>
        </div>
        <span class="font-semibold text-gray-900">Ronadamar</span>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 py-6">
        <div class="space-y-1 px-3">
            @php
                $currentRoute = request()->route()->getName();
                $navItems = [
                    'dashboard' => ['icon' => 'fas fa-tachometer-alt', 'label' => 'Dashboard'],
                    'products.index' => ['icon' => 'fas fa-boxes', 'label' => 'Manajemen Stok'],
                    'activity-logs.index' => ['icon' => 'fas fa-history', 'label' => 'Log History'],
                ];
            @endphp

            @foreach($navItems as $route => $item)
                @php
                    $isActive = $currentRoute === $route || str_starts_with($currentRoute, explode('.', $route)[0]);
                @endphp
                <a href="{{ route($route) }}" 
                   class="w-full flex items-center px-3 py-3 rounded-lg relative transition-all duration-200 group {{ $isActive ? 'text-blue-600 bg-blue-50' : 'text-gray-600 hover:text-blue-600 hover:bg-blue-50/50' }}">
                    @if($isActive)
                        <div class="absolute left-0 top-1/6 -translate-y-1/2 w-1 h-8 bg-blue-600 rounded-r-full"></div>
                    @endif
                    <i class="{{ $item['icon'] }} w-5 mr-3"></i>
                    <span>{{ $item['label'] }}</span>
                </a>
            @endforeach
        </div>
    </nav>
</aside>