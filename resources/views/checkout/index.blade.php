<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-2xl font-bold mb-6">Checkout</h1>

                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if($cartItems->isEmpty())
                        <div class="text-center py-8">
                            <p class="text-gray-600">Keranjang belanja Anda kosong.</p>
                            <a href="{{ route('products.index') }}" class="mt-4 inline-block bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
                                Lanjut Belanja
                            </a>
                        </div>
                    @else
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                            <!-- Cart Items -->
                            <div class="lg:col-span-2">
                                <h2 class="text-lg font-semibold mb-4">Item Keranjang</h2>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($cartItems as $item)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900">{{ $item->product->name }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900">Rp {{ number_format($item->product->price, 2, ',', '.') }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900">{{ $item->quantity }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900">Rp {{ number_format($item->product->price * $item->quantity, 2, ',', '.') }}</div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Order Summary -->
                            <div>
                                <h2 class="text-lg font-semibold mb-4">Ringkasan Pesanan</h2>
                                <div class="bg-gray-50 p-6 rounded-lg">
                                    <div class="flex justify-between mb-2">
                                        <span class="text-gray-600">Subtotal:</span>
                                        <span class="font-medium">Rp {{ number_format($totalAmount, 2, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between mb-2">
                                        <span class="text-gray-600">Biaya Pengiriman:</span>
                                        <span class="font-medium">Rp 0</span>
                                    </div>
                                    <div class="border-t border-gray-200 pt-2 mt-2">
                                        <div class="flex justify-between mb-2">
                                            <span class="text-gray-600">Total:</span>
                                            <span class="font-bold text-lg">Rp {{ number_format($totalAmount, 2, ',', '.') }}</span>
                                        </div>
                                    </div>

                                    <div class="mt-4">
                                        <p class="text-sm text-gray-600">Saldo Anda:</p>
                                        <p class="font-semibold">Rp {{ number_format($balance, 2, ',', '.') }}</p>
                                    </div>

                                    @if($hasSufficientBalance)
                                        <div class="mt-4 p-3 bg-green-100 text-green-800 rounded-md text-sm">
                                            <i class="fas fa-check-circle mr-2"></i>Saldo mencukupi untuk pembayaran
                                        </div>
                                    @else
                                        <div class="mt-4 p-3 bg-red-100 text-red-800 rounded-md text-sm">
                                            <i class="fas fa-exclamation-triangle mr-2"></i>Saldo tidak mencukupi
                                        </div>
                                    @endif

                                    <form method="POST" action="{{ route('checkout.process') }}" class="mt-6">
                                        @csrf
                                        <button 
                                            type="submit" 
                                            class="w-full bg-green-600 text-white py-3 px-4 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2
                                                {{ !$hasSufficientBalance ? 'opacity-50 cursor-not-allowed' : '' }}"
                                            {{ !$hasSufficientBalance ? 'disabled' : '' }}
                                        >
                                            Bayar dengan Saldo
                                        </button>
                                    </form>

                                    @if(!$hasSufficientBalance)
                                        <a href="{{ route('topup.create') }}" class="w-full mt-2 block text-center bg-yellow-600 text-white py-3 px-4 rounded-md hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2">
                                            Top Up Saldo
                                        </a>
                                    @endif

                                    <a href="{{ route('cart.index') }}" class="w-full mt-2 block text-center bg-gray-600 text-white py-3 px-4 rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                                        Kembali ke Keranjang
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>