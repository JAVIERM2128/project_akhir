@extends('layouts.admin')

@section('title', __('Pengaturan Toko'))
@section('content')

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-6">Edit Pengaturan Toko</h3>

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('admin.store-setting.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Nama Toko -->
                        <div class="mb-6">
                            <x-input-label for="store_name" :value="__('Nama Toko')" />
                            <x-text-input id="store_name" class="block mt-1 w-full" type="text" name="store_name" :value="old('store_name', $setting->store_name)" required />
                            <x-input-error :messages="$errors->get('store_name')" class="mt-2" />
                        </div>

                        <!-- Logo -->
                        <div class="mb-6">
                            <x-input-label for="logo" :value="__('Logo Toko')" />
                            
                            @if($setting->logo_path)
                                <div class="mb-3">
                                    <img src="{{ asset('storage/logos/' . $setting->logo_path) }}" alt="Logo Toko" class="h-24 w-24 object-contain rounded">
                                </div>
                            @endif
                            
                            <input type="file" id="logo" class="block mt-1 w-full" name="logo" accept="image/*" />
                            <x-input-error :messages="$errors->get('logo')" class="mt-2" />
                            <p class="text-sm text-gray-600 mt-1">Format: JPEG, PNG, JPG | Maksimal: 2MB</p>
                        </div>

                        <!-- Kontak WA -->
                        <div class="mb-6">
                            <x-input-label for="contact_phone" :value="__('Nomor Kontak WhatsApp')" />
                            <x-text-input id="contact_phone" class="block mt-1 w-full" type="tel" name="contact_phone" :value="old('contact_phone', $setting->contact_phone)" />
                            <x-input-error :messages="$errors->get('contact_phone')" class="mt-2" />
                        </div>

                        <!-- Alamat -->
                        <div class="mb-6">
                            <x-input-label for="address" :value="__('Alamat')" />
                            <textarea id="address" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" name="address" rows="3">{{ old('address', $setting->address) }}</textarea>
                            <x-input-error :messages="$errors->get('address')" class="mt-2" />
                        </div>

                        <!-- Deskripsi -->
                        <div class="mb-6">
                            <x-input-label for="description" :value="__('Deskripsi Toko')" />
                            <textarea id="description" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" name="description" rows="4">{{ old('description', $setting->description) }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <x-primary-button class="ms-4">
                                {{ __('Perbarui Pengaturan') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection