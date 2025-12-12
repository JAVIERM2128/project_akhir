<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="text-2xl font-bold mb-6">Keranjang Belanja</h2>

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if($cartItems->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kuantitas</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($cartItems as $item)
                                    <tr class="cart-item" data-cart-id="{{ $item->id }}">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-20 w-20">
                                                    @if($item->product->image)
                                                        <img class="h-20 w-20 rounded-md object-cover" src="{{ asset('storage/products/' . $item->product->image) }}" alt="{{ $item->product->name }}">
                                                    @else
                                                        <div class="bg-gray-200 border-2 border-dashed rounded-xl w-full h-20 flex items-center justify-center text-gray-500">
                                                            No Image
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">{{ $item->product->name }}</div>
                                                    <div class="text-sm text-gray-500">{{ $item->product->description }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">Rp {{ number_format($item->product->price, 2, ',', '.') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <input type="number" 
                                                       min="1" 
                                                       max="{{ $item->product->stock }}" 
                                                       value="{{ $item->quantity }}" 
                                                       class="quantity-input w-20 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                                                       data-cart-id="{{ $item->id }}">
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 font-semibold subtotal" data-cart-id="{{ $item->id }}">Rp {{ number_format($item->product->price * $item->quantity, 2, ',', '.') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <button class="remove-item text-red-600 hover:text-red-900" data-cart-id="{{ $item->id }}">Hapus</button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-gray-50">
                                    <tr>
                                        <td colspan="3" class="px-6 py-4 text-right text-sm font-medium text-gray-900">Total</td>
                                        <td colspan="2" class="px-6 py-4 text-sm font-bold text-gray-900">Rp {{ number_format($total, 2, ',', '.') }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="mt-8 flex justify-end space-x-4">
                            <a href="{{ route('products.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Lanjut Belanja
                            </a>
                            <a href="{{ route('checkout') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Checkout
                            </a>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Keranjang kosong</h3>
                            <p class="mt-1 text-sm text-gray-500">Anda belum menambahkan produk apapun ke keranjang.</p>
                            <div class="mt-6">
                                <a href="{{ route('products.index') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                    Belanja Sekarang
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Update quantity AJAX
            const quantityInputs = document.querySelectorAll('.quantity-input');
            quantityInputs.forEach(input => {
                input.addEventListener('change', function() {
                    const cartId = this.getAttribute('data-cart-id');
                    const quantity = this.value;
                    
                    // Validate quantity doesn't exceed stock
                    const maxStock = parseInt(this.getAttribute('max'));
                    if(parseInt(quantity) > maxStock) {
                        alert('Jumlah produk melebihi stok yang tersedia.');
                        this.value = maxStock;
                        return;
                    }
                    
                    fetch(`/cart/update/${cartId}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            quantity: quantity
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if(data.success) {
                            // Update subtotal for this item
                            const subtotalEl = document.querySelector(`.subtotal[data-cart-id="${cartId}"]`);
                            subtotalEl.textContent = data.formatted_subtotal;
                            
                            // Update total in footer
                            const totalRows = document.querySelectorAll('.cart-item');
                            let newTotal = 0;
                            
                            totalRows.forEach(row => {
                                const cartId = row.getAttribute('data-cart-id');
                                const quantityInput = document.querySelector(`.quantity-input[data-cart-id="${cartId}"]`);
                                const price = parseFloat(row.querySelector('.text-sm.text-gray-900').textContent.replace(/[^\d,]/g, '').replace(',', '.'));
                                const quantity = parseInt(quantityInput.value);
                                newTotal += price * quantity;
                            });
                            
                            const totalEl = document.querySelector('tfoot td:last-child');
                            totalEl.textContent = 'Rp ' + newTotal.toLocaleString('id-ID', {minimumFractionDigits: 2, maximumFractionDigits: 2}).replace(/,/g, ',');
                        } else {
                            alert(data.error);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
                });
            });
            
            // Remove item
            const removeButtons = document.querySelectorAll('.remove-item');
            removeButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const cartId = this.getAttribute('data-cart-id');
                    if(confirm('Apakah Anda yakin ingin menghapus item ini dari keranjang?')) {
                        window.location.href = `/cart/remove/${cartId}`;
                    }
                });
            });
        });
    </script>
</x-app-layout>