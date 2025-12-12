<x-guest-layout>
    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-green-500 to-green-700 text-white">
        <div class="container mx-auto px-4 py-16">
            <div class="flex flex-col md:flex-row items-center">
                <div class="md:w-1/2 mb-10 md:mb-0">
                    <h1 class="text-4xl md:text-5xl font-bold mb-4">Selamat Datang di Toko Sayur Segar</h1>
                    <p class="text-xl mb-8">Menyediakan berbagai jenis sayuran segar langsung dari petani lokal</p>
                    <a href="#products" class="bg-yellow-500 hover:bg-yellow-600 text-gray-900 font-bold py-3 px-6 rounded-full text-lg transition duration-300">
                        Lihat Produk
                    </a>
                </div>
                <div class="md:w-1/2 flex justify-center">
                    <div class="bg-white rounded-lg shadow-xl p-4 w-full max-w-md">
                        <img src="https://images.unsplash.com/photo-1542838132-92c53300491e?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=600&q=80" alt="Fresh Vegetables" class="w-full h-auto rounded">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Products Section -->
    <section id="products" class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-800 mb-4">Produk Unggulan</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">Temukan berbagai jenis sayuran segar dan berkualitas tinggi yang tersedia di toko kami</p>
            </div>

            @if($featuredProducts->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($featuredProducts as $product)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-300">
                        @if($product->image)
                            <img src="{{ asset('storage/products/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-48 object-cover">
                        @else
                            <div class="bg-gray-200 border-2 border-dashed rounded-xl w-full h-48 flex items-center justify-center text-gray-500">
                                No Image
                            </div>
                        @endif
                        <div class="p-6">
                            <h3 class="text-xl font-semibold text-gray-800 mb-2">{{ $product->name }}</h3>
                            <p class="text-gray-600 mb-4 line-clamp-2">{{ $product->description }}</p>
                            <div class="flex justify-between items-center">
                                <span class="text-lg font-bold text-green-600">Rp {{ number_format($product->price, 2, ',', '.') }}</span>
                                <span class="text-sm text-gray-500">Stok: {{ $product->stock }}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <p class="text-gray-600 text-lg">Belum ada produk tersedia.</p>
                </div>
            @endif

            <div class="text-center mt-12">
                <a href="{{ route('products.index') ?? '#' }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-8 rounded-full text-lg transition duration-300">
                    Lihat Semua Produk
                </a>
            </div>
        </div>
    </section>
</x-guest-layout>