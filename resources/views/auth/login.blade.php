@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="flex h-screen">
    <!-- Left Column - Illustration -->
    <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-blue-50 to-blue-100 items-center justify-center p-12">
        <div class="max-w-md text-center">
            <img src="https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?w=600&q=80" 
                 alt="Warehouse logistics" 
                 class="w-full h-auto rounded-lg shadow-lg mb-8">
            <h2 class="text-2xl font-bold text-blue-600 mb-4">Ronadamar Sejahtera</h2>
            <p class="text-gray-600">Modern inventory management solution for your business</p>
        </div>
    </div>

    <!-- Right Column - Login Form -->
    <div class="flex-1 flex items-center justify-center p-8 bg-white">
        <div class="w-full max-w-md p-8 shadow-lg border border-gray-200 rounded-lg">
            <div class="flex flex-col items-center mb-8">
                <div class="w-16 h-16 bg-blue-600 rounded-xl flex items-center justify-center mb-4">
                    <i class="fas fa-box text-white text-2xl"></i>
                </div>
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Welcome Admin</h1>
                <p class="text-gray-500">Sign in to access your dashboard</p>
            </div>

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf
                <div class="space-y-2">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ old('email') }}"
                           placeholder="admin@ronadamar.com" 
                           class="w-full h-11 px-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           required>
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           placeholder="Enter your password" 
                           class="w-full h-11 px-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           required
                           autocomplete="off">
                </div>

                <button type="submit" 
                        class="w-full h-11 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors">
                    Sign In
                </button>
            </form>
        </div>
    </div>
</div>
@endsection