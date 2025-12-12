<x-guest-layout>
    <div class="py-12">
        <div class="container mx-auto px-4">
            <div class="max-w-md mx-auto bg-white rounded-lg shadow-lg p-8 text-center">
                <div class="text-red-500 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                </div>
                
                <h1 class="text-2xl font-bold text-gray-800 mb-2">Produk Tidak Ditemukan</h1>
                <p class="text-gray-600 mb-6">
                    Maaf, produk dengan ID <span class="font-semibold">{{ $productId ?? 'tertentu' }}</span> tidak dapat ditemukan di sistem kami.
                </p>
                
                <a href="{{ route('products.index') }}" 
                   class="inline-block bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg transition duration-300">
                    Kembali ke Katalog
                </a>
            </div>
        </div>
    </div>
</x-guest-layout>