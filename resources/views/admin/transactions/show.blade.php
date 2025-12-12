<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Transaksi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold">Detail Transaksi #{{ $transaction->id }}</h3>
                        <a href="{{ route('admin.transactions.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Kembali
                        </a>
                    </div>

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Informasi Transaksi -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-gray-700 mb-3">Informasi Transaksi</h4>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">ID Transaksi:</span>
                                    <span class="font-medium">#{{ $transaction->id }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Status:</span>
                                    <span class="font-medium">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $transaction->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                               ($transaction->status === 'paid' ? 'bg-green-100 text-green-800' : 
                                               ($transaction->status === 'cancelled' ? 'bg-red-100 text-red-800' : 
                                               ($transaction->status === 'shipped' ? 'bg-blue-100 text-blue-800' : 
                                               'bg-gray-100 text-gray-800'))) }}">
                                            {{ ucfirst($transaction->status) }}
                                        </span>
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Total:</span>
                                    <span class="font-medium">Rp {{ number_format($transaction->total_amount, 2, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Metode Pembayaran:</span>
                                    <span class="font-medium">{{ $transaction->payment_method ?? '-' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Tanggal:</span>
                                    <span class="font-medium">{{ $transaction->created_at->format('d M Y H:i') }}</span>
                                </div>
                                @if($transaction->paid_at)
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Tanggal Dibayar:</span>
                                    <span class="font-medium">{{ $transaction->paid_at->format('d M Y H:i') }}</span>
                                </div>
                                @endif
                            </div>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-gray-700 mb-3">Informasi Pelanggan</h4>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Nama:</span>
                                    <span class="font-medium">{{ $transaction->user->name }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Email:</span>
                                    <span class="font-medium">{{ $transaction->user->email }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Daftar Produk -->
                    <div class="mb-8">
                        <h4 class="font-semibold text-gray-700 mb-3">Daftar Produk</h4>
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
                                    @foreach($transaction->transactionItems as $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $item->product->name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            Rp {{ number_format($item->price, 2, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $item->quantity }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            Rp {{ number_format($item->price * $item->quantity, 2, ',', '.') }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Update Status -->
                    <div class="mb-8">
                        <h4 class="font-semibold text-gray-700 mb-3">Ubah Status Transaksi</h4>
                        <form action="{{ route('admin.transactions.updateStatus', $transaction) }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status Baru</label>
                                    <select name="status" id="status" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        <option value="pending" {{ $transaction->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="paid" {{ $transaction->status == 'paid' ? 'selected' : '' }}>Paid</option>
                                        <option value="shipped" {{ $transaction->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                        <option value="delivered" {{ $transaction->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                        <option value="cancelled" {{ $transaction->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label for="note" class="block text-sm font-medium text-gray-700 mb-1">Catatan (Opsional)</label>
                                    <input type="text" name="note" id="note" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Tambahkan catatan jika perlu">
                                </div>
                            </div>
                            
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Perbarui Status
                            </button>
                        </form>
                    </div>

                    <!-- History Status -->
                    <div>
                        <h4 class="font-semibold text-gray-700 mb-3">Riwayat Perubahan Status</h4>
                        @if($transaction->statusHistories->count() > 0)
                            <div class="space-y-3">
                                @foreach($transaction->statusHistories as $history)
                                    <div class="border-l-4 border-blue-500 pl-4 py-2 bg-gray-50">
                                        <div class="flex justify-between">
                                            <div>
                                                <span class="font-medium">{{ $history->old_status }} → {{ $history->new_status }}</span>
                                                @if($history->note)
                                                    <p class="text-sm text-gray-600 mt-1">{{ $history->note }}</p>
                                                @endif
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ $history->changed_at->format('d M Y H:i') }}
                                                @if($history->user)
                                                    <br>oleh {{ $history->user->name }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-600">Belum ada riwayat perubahan status.</p>
                        @endif
                    </div>

                    <!-- Upload Resi untuk Produk Fashion -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <h4 class="font-semibold text-gray-700 mb-3">Upload Resi Pengiriman</h4>

                        @if($transaction->receipt_path)
                            <div class="mb-4 p-3 bg-green-50 rounded-md">
                                <p class="text-green-700 mb-2">Resi telah diupload:</p>
                                <a href="{{ asset('storage/receipts/' . $transaction->receipt_path) }}" target="_blank" class="text-blue-600 hover:underline">
                                    Lihat Resi ({{ pathinfo($transaction->receipt_path, PATHINFO_EXTENSION) }})
                                </a>
                            </div>
                        @endif

                        <form action="{{ route('admin.transactions.uploadReceipt', $transaction) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-4">
                                <label for="receipt" class="block text-sm font-medium text-gray-700 mb-1">File Resi</label>
                                <input type="file" name="receipt" id="receipt" class="block w-full text-sm text-gray-500
                                    file:mr-4 file:py-2 file:px-4
                                    file:rounded-md file:border-0
                                    file:text-sm file:font-semibold
                                    file:bg-blue-50 file:text-blue-700
                                    hover:file:bg-blue-100" accept=".jpeg,.jpg,.png,.pdf">
                                <p class="text-xs text-gray-500 mt-1">Format: JPEG, PNG, PDF | Maksimal: 2MB</p>
                            </div>
                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Upload Resi
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>