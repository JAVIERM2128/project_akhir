<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold">Detail Transaksi #{{ $transaction->id }}</h2>
                        <a href="{{ route('transactions.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Kembali
                        </a>
                    </div>

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
                                               ($transaction->status === 'delivered' ? 'bg-purple-100 text-purple-800' :
                                               'bg-gray-100 text-gray-800')))) }}">
                                            {{ ucfirst($transaction->status) }}
                                        </span>
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Total:</span>
                                    <span class="font-medium">Rp {{ number_format($transaction->total_amount, 2, ',', '.') }}</span>
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

                    <!-- Bukti Pembayaran atau Catatan Pengiriman -->
                    @if($transaction->receipt_path)
                    <div class="mb-8">
                        <h4 class="font-semibold text-gray-700 mb-3">Bukti Pembayaran</h4>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <a href="{{ asset('storage/receipts/' . $transaction->receipt_path) }}" target="_blank" class="text-blue-600 hover:underline">
                                Lihat Bukti Pembayaran ({{ pathinfo($transaction->receipt_path, PATHINFO_EXTENSION) }})
                            </a>
                        </div>
                    </div>
                    @endif

                    <!-- Riwayat Status -->
                    <div>
                        <h4 class="font-semibold text-gray-700 mb-3">Riwayat Status</h4>
                        @if($transaction->statusHistories->count() > 0)
                            <div class="space-y-3">
                                @foreach($transaction->statusHistories->sortByDesc('created_at') as $history)
                                    <div class="border-l-4 border-blue-500 pl-4 py-2 bg-gray-50">
                                        <div class="flex justify-between">
                                            <div>
                                                <span class="font-medium">{{ $history->old_status }} → {{ $history->new_status }}</span>
                                                @if($history->note)
                                                    <p class="text-sm text-gray-600 mt-1">{{ $history->note }}</p>
                                                @endif
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ $history->created_at->format('d M Y H:i') }}
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
                </div>
            </div>
        </div>
    </div>
</x-app-layout>