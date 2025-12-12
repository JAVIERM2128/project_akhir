<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', config('app.name', 'Toko Sayur'))</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            <!-- Sidebar Desktop -->
            <div class="fixed inset-y-0 left-0 w-64 bg-white shadow-lg z-10 hidden md:flex md:flex-col">
                <div class="flex items-center justify-center h-16 px-4 bg-green-600 text-white">
                    <h1 class="text-xl font-bold">ADMIN PANEL</h1>
                </div>
                
                <div class="flex-1 overflow-y-auto mt-4">
                    <nav class="px-4 space-y-2">
                        <a href="{{ route('admin.dashboard') }}" 
                           class="flex items-center px-4 py-2 text-sm font-medium rounded-md 
                           {{ request()->routeIs('admin.dashboard') ? 'bg-green-100 text-green-800' : 'text-gray-700 hover:bg-gray-100' }}">
                            <svg class="h-5 w-5 mr-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                            </svg>
                            Dashboard
                        </a>

                        <a href="{{ route('admin.products.index') }}"
                           class="flex items-center px-4 py-2 text-sm font-medium rounded-md
                           {{ request()->routeIs('admin.products.*') ? 'bg-green-100 text-green-800' : 'text-gray-700 hover:bg-gray-100' }}">
                            <svg class="h-5 w-5 mr-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd" />
                            </svg>
                            Produk
                        </a>

                        <a href="{{ route('admin.categories.index') }}"
                           class="flex items-center px-4 py-2 text-sm font-medium rounded-md
                           {{ request()->routeIs('admin.categories.*') ? 'bg-green-100 text-green-800' : 'text-gray-700 hover:bg-gray-100' }}">
                            <svg class="h-5 w-5 mr-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A.997.997 0 012 10V5a3 3 0 013-3h5c.256 0 .512.098.707.293l7 7zM5 6a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                            </svg>
                            Kategori
                        </a>

                        <a href="{{ route('admin.transactions.index') }}"
                           class="flex items-center px-4 py-2 text-sm font-medium rounded-md
                           {{ request()->routeIs('admin.transactions.*') ? 'bg-green-100 text-green-800' : 'text-gray-700 hover:bg-gray-100' }}">
                            <svg class="h-5 w-5 mr-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 1a1 1 0 011 1v1h3a1 1 0 110 2H6a1 1 0 110-2h3V2a1 1 0 011-1zm-3 4a1 1 0 100 2h6a1 1 0 100-2H7zm-3 5a1 1 0 011-1h12a1 1 0 110 2H5a1 1 0 01-1-1zm0 3a1 1 0 100 2h12a1 1 0 100-2H4z" clip-rule="evenodd" />
                            </svg>
                            Transaksi
                        </a>

                        <a href="{{ route('admin.users.index') }}"
                           class="flex items-center px-4 py-2 text-sm font-medium rounded-md
                           {{ request()->routeIs('admin.users.*') ? 'bg-green-100 text-green-800' : 'text-gray-700 hover:bg-gray-100' }}">
                            <svg class="h-5 w-5 mr-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
                            </svg>
                            Pengguna
                        </a>

                        <a href="{{ route('admin.topups.index') }}"
                           class="flex items-center px-4 py-2 text-sm font-medium rounded-md
                           {{ request()->routeIs('admin.topups.*') ? 'bg-green-100 text-green-800' : 'text-gray-700 hover:bg-gray-100' }}">
                            <svg class="h-5 w-5 mr-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4 2a2 2 0 00-2 2v11a3 3 0 106 0V4a2 2 0 00-2-2H4zm1 14a1 1 0 100-2 1 1 0 000 2zm5-1.757l4.9-4.9a2 2 0 000-2.828L13.485 5.1a2 2 0 00-2.828 0L10 5.757v8.486zM16 18H9.071l6-6H16a2 2 0 012 2v2a2 2 0 01-2 2z" clip-rule="evenodd" />
                            </svg>
                            Top Up
                        </a>

                        <a href="{{ route('admin.reports.sales') }}"
                           class="flex items-center px-4 py-2 text-sm font-medium rounded-md
                           {{ request()->routeIs('admin.reports.*') ? 'bg-green-100 text-green-800' : 'text-gray-700 hover:bg-gray-100' }}">
                            <svg class="h-5 w-5 mr-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd" />
                            </svg>
                            Laporan
                        </a>

                        <a href="{{ route('admin.store-setting.edit') }}"
                           class="flex items-center px-4 py-2 text-sm font-medium rounded-md
                           {{ request()->routeIs('admin.store-setting.*') ? 'bg-green-100 text-green-800' : 'text-gray-700 hover:bg-gray-100' }}">
                            <svg class="h-5 w-5 mr-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 00-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                            </svg>
                            Pengaturan Toko
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Main Content -->
            <div class="md:ml-64">
                <!-- Top Navigation Bar -->
                <nav class="bg-white border-b border-gray-200 px-4 py-3 flex items-center justify-between">
                    <div class="flex items-center">
                        <!-- Mobile Menu Button -->
                        <button @click="open = ! open" class="md:hidden mr-4">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>

                        <h2 class="text-lg font-semibold text-gray-800">
                            @yield('title')
                        </h2>
                    </div>

                    <div class="flex items-center space-x-4">
                        <a href="{{ route('home') }}"
                           class="text-sm font-medium text-green-600 hover:text-green-800 px-3 py-2 rounded-md transition duration-200">
                            ← Ke Beranda
                        </a>
                        <a href="{{ route('products.index') }}"
                           class="text-sm font-medium text-gray-600 hover:text-gray-800 px-3 py-2 rounded-md transition duration-200">
                            Katalog Produk
                        </a>
                    </div>
                </nav>

                    <div class="flex items-center">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                    <div>{{ Auth::user()->name }}</div>
                                    <div class="ms-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <x-dropdown-link :href="route('profile.edit')">
                                    {{ __('Profile') }}
                                </x-dropdown-link>
                                
                                <x-dropdown-link :href="route('home')">
                                    {{ __('Ke Beranda') }}
                                </x-dropdown-link>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')"
                                            onclick="event.preventDefault();
                                                        this.closest('form').submit();">
                                        {{ __('Log Out') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                </nav>

                <!-- Page Content -->
                <main class="p-4 md:p-6">
                    @yield('content')
                </main>
            </div>
        </div>

        <script>
            // Initialize Alpine.js data if needed
            document.addEventListener('DOMContentLoaded', function() {
                // Any JavaScript initialization can go here if needed
            });
        </script>
    </body>
</html>