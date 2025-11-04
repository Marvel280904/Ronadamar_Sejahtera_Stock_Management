<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Management - @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    @if(auth()->check())
    <div class="flex h-screen">
        <!-- Sidebar -->
        @include('layouts.sidebar')
        
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            @include('layouts.header')
            
            <!-- Main Content -->
            <main class="flex-1 overflow-auto p-6">
                @yield('content')
            </main>
        </div>
    </div>
    @else
        @yield('content')
    @endif

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    @stack('scripts')
</body>
</html>