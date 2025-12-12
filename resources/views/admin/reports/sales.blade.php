@extends('layouts.admin')

@section('title', __('Laporan Penjualan'))
@section('content')

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Filters -->
                    <form method="GET" action="{{ route('admin.reports.sales') }}" class="mb-8">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Periode</label>
                                <select name="type" id="type" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="daily" {{ $type === 'daily' ? 'selected' : '' }}>Harian</option>
                                    <option value="weekly" {{ $type === 'weekly' ? 'selected' : '' }}>Mingguan</option>
                                    <option value="monthly" {{ $type === 'monthly' ? 'selected' : '' }}>Bulanan</option>
                                </select>
                            </div>
                            
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Awal</label>
                                <input type="date" name="start_date" id="start_date" value="{{ $startDate }}" 
                                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                            
                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Akhir</label>
                                <input type="date" name="end_date" id="end_date" value="{{ $endDate }}" 
                                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                            
                            <div class="flex items-end">
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Filter
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Summary Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="bg-blue-50 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold text-blue-800">Total Transaksi</h3>
                            <p class="text-3xl font-bold text-blue-600">{{ $totalTransactions }}</p>
                        </div>
                        
                        <div class="bg-green-50 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold text-green-800">Total Penjualan</h3>
                            <p class="text-3xl font-bold text-green-600">{{ $totalSales }}</p>
                        </div>
                        
                        <div class="bg-purple-50 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold text-purple-800">Total Pendapatan</h3>
                            <p class="text-3xl font-bold text-purple-600">Rp {{ number_format($totalRevenue, 2, ',', '.') }}</p>
                        </div>
                    </div>

                    <!-- Export Button -->
                    <div class="flex justify-end mb-4">
                        <a href="{{ route('admin.reports.sales.export', ['type' => $type, 'start_date' => $startDate, 'end_date' => $endDate]) }}"
                           class="bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-md mr-2">
                            Ekspor Excel
                        </a>
                        <a href="{{ route('admin.reports.sales.pdf', ['type' => $type, 'start_date' => $startDate, 'end_date' => $endDate]) }}"
                           class="bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded-md">
                            Ekspor PDF
                        </a>
                    </div>

                    <!-- Report Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Periode</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Transaksi</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Produk Terjual</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pendapatan</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($reportData as $data)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $data->date ?? $data['date'] }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $data->transaction_count ?? $data['transaction_count'] }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $data->total_quantity ?? $data['total_quantity'] }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">Rp {{ number_format($data->total_revenue ?? $data['total_revenue'], 2, ',', '.') }}</div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                        Tidak ada data laporan untuk periode yang dipilih.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection