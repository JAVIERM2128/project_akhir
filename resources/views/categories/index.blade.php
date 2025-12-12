<x-guest-layout>
    <div class="py-12">
        <div class="container mx-auto px-4">
            <h1 class="text-3xl font-bold text-gray-800 mb-8 text-center">
                @if(isset($category) && $category)
                    {{ $category->name }}
                @else
                    Katalog Produk
                @endif
            </h1>

            <!-- Form Filter -->
            <div class="bg-white p-6 rounded-lg shadow-md mb-8">
                <h2 class="text-xl font-semibold text-gray-700 mb-4">Filter Produk</h2>
                <form method="GET" action="{{ request()->fullUrl() }}" id="filter-form">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Pencarian Nama Produk -->
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Nama Produk</label>
                            <input type="text" id="search" name="search" value="{{ request('search') }}" placeholder="Cari produk..." class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                        </div>

                        <!-- Filter Harga Minimum -->
                        <div>
                            <label for="min_price" class="block text-sm font-medium text-gray-700 mb-1">Harga Min</label>
                            <input type="number" id="min_price" name="min_price" value="{{ request('min_price') }}" placeholder="Rp" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                        </div>

                        <!-- Filter Harga Maksimum -->
                        <div>
                            <label for="max_price" class="block text-sm font-medium text-gray-700 mb-1">Harga Max</label>
                            <input type="number" id="max_price" name="max_price" value="{{ request('max_price') }}" placeholder="Rp" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                        </div>

                        <!-- Filter Stok Minimum -->
                        <div>
                            <label for="min_stock" class="block text-sm font-medium text-gray-700 mb-1">Stok Min</label>
                            <input type="number" id="min_stock" name="min_stock" value="{{ request('min_stock') }}" placeholder="Jumlah" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                        </div>

                        <!-- Sorting -->
                        <div>
                            <label for="sort" class="block text-sm font-medium text-gray-700 mb-1">Urutkan</label>
                            <select id="sort" name="sort" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                                <option value="price_low_high" {{ request('sort') == 'price_low_high' ? 'selected' : '' }}>Harga: Rendah ke Tinggi</option>
                                <option value="price_high_low" {{ request('sort') == 'price_high_low' ? 'selected' : '' }}>Harga: Tinggi ke Rendah</option>
                                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nama: A ke Z</option>
                                <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Nama: Z ke A</option>
                            </select>
                        </div>

                        <!-- Kategori Filter (jika tidak sedang difilter berdasarkan kategori tertentu) -->
                        @if(!isset($category) || !$category)
                        <div>
                            <label for="category_filter" class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                            <select id="category_filter" name="category_filter" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                                <option value="">Semua Kategori</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ request('category_filter') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                    </div>

                    <div class="mt-4 flex justify-end">
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                            Terapkan Filter
                        </button>
                        <button type="button" onclick="clearFilters()" class="ml-2 px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                            Reset Filter
                        </button>
                    </div>
                </form>
            </div>

            <!-- Kategori Filter -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-700 mb-4">Kategori</h2>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('products.index') . '?' . http_build_query(array_diff_key(request()->query(), ['category_filter' => ''])) }}"
                       class="px-4 py-2 rounded-full {{ !isset($category) || !$category ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                        Semua Produk
                    </a>
                    @foreach($categories as $cat)
                        <a href="{{ route('products.category', $cat->id) . '?' . http_build_query(array_diff_key(request()->query(), ['category_filter' => ''])) }}"
                           class="px-4 py-2 rounded-full {{ isset($category) && $category && $category->id == $cat->id ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                            {{ $cat->name }} ({{ $cat->products_count }})
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Jumlah Hasil Pencarian -->
            @if(isset($products) && $products)
                <div class="mb-4">
                    <p class="text-gray-600">
                        Menampilkan {{ $products->count() }} dari {{ $totalProducts }} produk
                        @if(request()->query())
                            <span class="text-green-600 font-medium">berdasarkan filter yang diterapkan</span>
                        @endif
                    </p>
                </div>
            @endif

            <!-- Produk Section -->
            @if($products && $products->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($products as $product)
                    <a href="{{ route('products.show', $product->id) }}" class="block">
                        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-300">
                            @if($product->image)
                                @php
                                    $imagePath = storage_path('app/public/products/' . $product->image);
                                    $imageExists = file_exists($imagePath);
                                @endphp
                                @if($imageExists)
                                    <img src="{{ asset('storage/products/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-48 object-cover">
                                @else
                                    <div class="bg-gray-200 border-2 border-dashed rounded-xl w-full h-48 flex items-center justify-center text-gray-500">
                                        No Image
                                    </div>
                                @endif
                            @else
                                <div class="bg-gray-200 border-2 border-dashed rounded-xl w-full h-48 flex items-center justify-center text-gray-500">
                                    No Image
                                </div>
                            @endif
                            <div class="p-4">
                                <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ $product->name }}</h3>
                                <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ $product->description }}</p>
                                <div class="flex justify-between items-center">
                                    <span class="text-lg font-bold text-green-600">Rp {{ number_format($product->price, 2, ',', '.') }}</span>
                                    <span class="text-sm {{ $product->stock > 0 ? 'text-green-600' : 'text-red-600' }}">
                                        Stok: {{ $product->stock }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $products->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <p class="text-gray-600 text-lg">
                        @if(request()->query() && !($category ?? null))
                            Tidak ada produk yang sesuai dengan filter yang diterapkan.
                        @elseif(isset($category) && $category)
                            Tidak ada produk pada kategori ini.
                        @else
                            Belum ada produk tersedia.
                        @endif
                    </p>
                </div>
            @endif
        </div>
    </div>

    <script>
        function clearFilters() {
            // Clear all filter inputs except category if on category page
            const search = document.getElementById('search');
            const minPrice = document.getElementById('min_price');
            const maxPrice = document.getElementById('max_price');
            const minStock = document.getElementById('min_stock');
            const sort = document.getElementById('sort');
            const categoryFilter = document.getElementById('category_filter');

            search.value = '';
            minPrice.value = '';
            maxPrice.value = '';
            minStock.value = '';
            sort.value = 'latest';

            if (categoryFilter) {
                categoryFilter.value = '';
            }

            document.getElementById('filter-form').submit();
        }

        // Update URL when sorting changes without page reload
        document.getElementById('sort').addEventListener('change', function() {
            document.getElementById('filter-form').submit();
        });
    </script>
</x-guest-layout>