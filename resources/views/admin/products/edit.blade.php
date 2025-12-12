<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Produk') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <x-input-label for="name" :value="__('Nama Produk')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $product->name)" required />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="description" :value="__('Deskripsi')" />
                            <textarea id="description" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" name="description" rows="4">{{ old('description', $product->description) }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="price" :value="__('Harga')" />
                            <x-text-input id="price" class="block mt-1 w-full" type="number" name="price" :value="old('price', $product->price)" required step="0.01" min="0" />
                            <x-input-error :messages="$errors->get('price')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="stock" :value="__('Stok')" />
                            <x-text-input id="stock" class="block mt-1 w-full" type="number" name="stock" :value="old('stock', $product->stock)" required min="0" />
                            <x-input-error :messages="$errors->get('stock')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="category_id" :value="__('Kategori')" />
                            <select id="category_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" name="category_id">
                                <option value="">Pilih Kategori</option>
                                @foreach(\App\Models\Category::all() as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="unit" :value="__('Unit')" />
                            <x-text-input id="unit" class="block mt-1 w-full" type="text" name="attributes[unit]" :value="old('attributes.unit', $product->attributes['unit'] ?? '')" placeholder="Contoh: kg, ons, ikat" />
                            <x-input-error :messages="$errors->get('attributes.unit')" class="mt-2" />
                            <p class="text-sm text-gray-600 mt-1">Satuan produk (misalnya kg, ons, ikat)</p>
                        </div>

                        <div class="mb-4">
                            <x-input-label for="image" :value="__('Gambar Produk')" />
                            @if($product->image)
                                @php
                                    $imagePath = storage_path('app/public/products/' . $product->image);
                                    $imageExists = file_exists($imagePath);
                                @endphp
                                @if($imageExists)
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/products/' . $product->image) }}" alt="{{ $product->name }}" class="h-32 w-32 object-cover rounded">
                                    </div>
                                @else
                                    <div class="mb-2">
                                        <p class="text-red-600">Gambar tidak ditemukan di server</p>
                                        <p class="text-sm text-gray-600 mt-1">Upload gambar baru untuk menggantinya</p>
                                    </div>
                                @endif
                            @endif
                            <input type="file" id="image" class="block mt-1 w-full" name="image" accept="image/*" />
                            <x-input-error :messages="$errors->get('image')" class="mt-2" />
                            <p class="text-sm text-gray-600 mt-1">Format: JPEG, PNG, JPG | Maksimal: 10MB (kosongkan jika tidak ingin mengganti gambar)</p>
                        </div>


                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('admin.products.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">
                                Batal
                            </a>
                            <x-primary-button class="ms-4">
                                {{ __('Update Produk') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>