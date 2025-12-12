<x-guest-layout>
    @if($product)
        <div class="py-12">
            <div class="container mx-auto px-4">
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <div class="flex flex-col md:flex-row">
                        <!-- Gambar Produk -->
                        <div class="md:w-1/2 p-6 flex items-center justify-center">
                            @if($product->image)
                                <img src="{{ asset('storage/products/' . $product->image) }}" 
                                     alt="{{ $product->name }}" 
                                     class="w-full h-96 object-contain rounded-lg">
                            @else
                                <div class="bg-gray-200 border-2 border-dashed rounded-xl w-full h-96 flex items-center justify-center text-gray-500">
                                    No Image
                                </div>
                            @endif
                        </div>
                        
                        <!-- Informasi Produk -->
                        <div class="md:w-1/2 p-6">
                            <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $product->name }}</h1>
                            
                            @if($product->category)
                                <span class="inline-block bg-green-100 text-green-800 text-sm px-3 py-1 rounded-full mb-4">
                                    {{ $product->category->name }}
                                </span>
                            @endif
                            
                            <div class="text-2xl font-bold text-green-600 mb-4">Rp {{ number_format($product->price, 2, ',', '.') }}</div>
                            
                            <div class="mb-4">
                                <span class="font-semibold text-gray-700">Stok:</span>
                                <span class="{{ $product->stock > 0 ? 'text-green-600' : 'text-red-600' }} font-semibold">
                                    {{ $product->stock }} {{ $product->category && str_contains(strtolower($product->category->name), 'sayur') ? 'item' : 'unit' }}
                                </span>
                            </div>
                            
                            <!-- Atribut Khusus berdasarkan jenis produk -->
                            @if($product->attributes)
                                <div class="mb-4">
                                    <h3 class="font-semibold text-gray-700 mb-2">Spesifikasi Produk</h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                        @if(isset($product->attributes['unit']))
                                            <div class="flex">
                                                <span class="text-gray-600 w-32">Unit:</span>
                                                <span class="text-gray-800 font-medium">{{ $product->attributes['unit'] }}</span>
                                            </div>
                                        @endif

                                        @if(isset($product->attributes['size']))
                                            <div class="flex">
                                                <span class="text-gray-600 w-32">Ukuran:</span>
                                                <span class="text-gray-800 font-medium">{{ $product->attributes['size'] }}</span>
                                            </div>
                                        @endif

                                        @if(isset($product->attributes['color']))
                                            <div class="flex">
                                                <span class="text-gray-600 w-32">Warna:</span>
                                                <span class="text-gray-800 font-medium">{{ $product->attributes['color'] }}</span>
                                            </div>
                                        @endif

                                        @if(isset($product->attributes['frame_material']))
                                            <div class="flex">
                                                <span class="text-gray-600 w-32">Material Rangka:</span>
                                                <span class="text-gray-800 font-medium">{{ $product->attributes['frame_material'] }}</span>
                                            </div>
                                        @endif

                                        @if(isset($product->attributes['wheel_size']))
                                            <div class="flex">
                                                <span class="text-gray-600 w-32">Ukuran Roda:</span>
                                                <span class="text-gray-800 font-medium">{{ $product->attributes['wheel_size'] }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <div class="mb-6">
                                <h3 class="font-semibold text-gray-700 mb-2">Deskripsi</h3>
                                <p class="text-gray-600">{{ $product->description ?: 'Tidak ada deskripsi tersedia.' }}</p>
                            </div>
                            
                            <!-- Tombol Tambah ke Keranjang -->
                            @if($product->stock > 0)
                                <form action="{{ route('cart.add', $product->id) }}" method="POST" class="mb-4">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <div class="flex items-center mb-4">
                                        <span class="mr-3">Jumlah:</span>
                                        <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}" 
                                               class="border border-gray-300 rounded-md px-3 py-2 w-20">
                                    </div>
                                    <button type="submit" 
                                            class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg transition duration-300">
                                        Tambah ke Keranjang
                                    </button>
                                </form>
                            @else
                                <div class="mb-4">
                                    <button type="button" 
                                            class="bg-gray-400 text-white font-bold py-3 px-6 rounded-lg cursor-not-allowed" 
                                            disabled>
                                        Stok Habis
                                    </button>
                                    <p class="text-red-600 mt-2">Produk saat ini tidak tersedia</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Rekomendasi Produk Terkait -->
                @if($relatedProducts && $relatedProducts->count() > 0)
                    <div class="mt-12">
                        <h2 class="text-2xl font-bold text-gray-800 mb-6">Produk Terkait</h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                            @foreach($relatedProducts as $relatedProduct)
                                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-300">
                                    @if($relatedProduct->image)
                                        <img src="{{ asset('storage/products/' . $relatedProduct->image) }}" 
                                             alt="{{ $relatedProduct->name }}" 
                                             class="w-full h-48 object-cover">
                                    @else
                                        <div class="bg-gray-200 border-2 border-dashed rounded-xl w-full h-48 flex items-center justify-center text-gray-500">
                                            No Image
                                        </div>
                                    @endif
                                    <div class="p-4">
                                        <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ $relatedProduct->name }}</h3>
                                        <div class="flex justify-between items-center">
                                            <span class="text-lg font-bold text-green-600">Rp {{ number_format($relatedProduct->price, 2, ',', '.') }}</span>
                                            <span class="text-sm {{ $relatedProduct->stock > 0 ? 'text-green-600' : 'text-red-600' }}">
                                                Stok: {{ $relatedProduct->stock }}
                                            </span>
                                        </div>
                                        <a href="{{ route('products.show', $relatedProduct->id) }}" 
                                           class="mt-3 inline-block bg-green-600 hover:bg-green-700 text-white text-center py-2 px-4 rounded-lg transition duration-300">
                                            Lihat Detail
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endif
</x-guest-layout>