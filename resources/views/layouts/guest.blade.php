<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Toko Sayur') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <!-- Navigation -->
        <nav class="bg-white shadow-md">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <!-- Logo -->
                        <div class="flex-shrink-0 flex items-center">
                            <a href="/" class="text-green-600 font-bold text-xl">
                                Toko Sayur
                            </a>
                        </div>
                        
                        <!-- Navigation Menu -->
                        <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                            <a href="{{ route('home') }}" class="text-gray-900 hover:border-gray-300 hover:text-gray-700 px-1 pt-1 font-medium">Beranda</a>
                            <a href="{{ route('products.index') }}" class="text-gray-900 hover:border-gray-300 hover:text-gray-700 px-1 pt-1 font-medium">Katalog</a>
                            <a href="#" class="text-gray-900 hover:border-gray-300 hover:text-gray-700 px-1 pt-1 font-medium">Tentang Kami</a>
                        </div>
                    </div>
                    
                    <div class="flex items-center">
                        <!-- Cart and Auth Links -->
                        <div class="flex items-center space-x-4">
                            @auth
                                @if(auth()->user()->role === 'admin')
                                    <a href="{{ route('admin.dashboard') }}" class="text-gray-700 hover:text-gray-900 px-3 py-2 font-medium">Admin</a>
                                @else
                                    <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-gray-900 px-3 py-2 font-medium">Dashboard</a>
                                @endif
                                <a href="{{ route('logout') }}" 
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
                                   class="text-gray-700 hover:text-gray-900 px-3 py-2 font-medium">
                                    Keluar
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                                    @csrf
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="text-gray-700 hover:text-gray-900 px-3 py-2 font-medium">Masuk</a>
                                <a href="{{ route('register') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md font-medium">Daftar</a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Mobile menu -->
            <div class="sm:hidden">
                <div class="pt-2 pb-3 space-y-1">
                    <a href="{{ route('home') }}" class="block pl-3 pr-4 py-2 text-gray-900 hover:bg-gray-50 font-medium">Beranda</a>
                    <a href="{{ route('products.index') }}" class="block pl-3 pr-4 py-2 text-gray-900 hover:bg-gray-50 font-medium">Katalog</a>
                    <a href="#" class="block pl-3 pr-4 py-2 text-gray-900 hover:bg-gray-50 font-medium">Tentang Kami</a>
                    
                    @auth
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="block pl-3 pr-4 py-2 text-gray-900 hover:bg-gray-50 font-medium">Admin</a>
                        @else
                            <a href="{{ route('dashboard') }}" class="block pl-3 pr-4 py-2 text-gray-900 hover:bg-gray-50 font-medium">Dashboard</a>
                        @endif
                        <a href="{{ route('logout') }}" 
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
                           class="block pl-3 pr-4 py-2 text-gray-900 hover:bg-gray-50 font-medium">
                            Keluar
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="block pl-3 pr-4 py-2 text-gray-900 hover:bg-gray-50 font-medium">Masuk</a>
                        <a href="{{ route('register') }}" class="block pl-3 pr-4 py-2 text-gray-900 hover:bg-gray-50 font-medium">Daftar</a>
                    @endauth
                </div>
            </div>
        </nav>

        <main>
            {{ $slot }}
        </main>
    </body>
</html>