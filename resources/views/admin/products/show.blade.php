<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Produk') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-4">
                        <h3 class="text-lg font-medium text-gray-900">{{ $product->name }}</h3>
                    </div>

                    @if($product->image)
                        @php
                            $imagePath = storage_path('app/public/products/' . $product->image);
                            $imageExists = file_exists($imagePath);
                        @endphp
                        @if($imageExists)
                            <div class="mb-4">
                                <img src="{{ asset('storage/products/' . $product->image) }}" alt="{{ $product->name }}" class="h-64 w-64 object-cover rounded">
                            </div>
                        @else
                            <div class="mb-4">
                                <p class="text-red-600">Gambar tidak ditemukan di server</p>
                            </div>
                        @endif
                    @endif

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Harga</p>
                            <p class="text-gray-900">Rp {{ number_format($product->price, 2, ',', '.') }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-gray-500">Stok</p>
                            <p class="text-gray-900">{{ $product->stock }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-gray-500">Kategori</p>
                            <p class="text-gray-900">{{ $product->category ? $product->category->name : '-' }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-gray-500">Dibuat pada</p>
                            <p class="text-gray-900">{{ $product->created_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>

                    <div class="mb-4">
                        <p class="text-sm font-medium text-gray-500">Deskripsi</p>
                        <p class="text-gray-900">{{ $product->description ?? '-' }}</p>
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <a href="{{ route('admin.products.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>