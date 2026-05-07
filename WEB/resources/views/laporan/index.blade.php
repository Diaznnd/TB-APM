@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Laporan Penjualan</h1>
            <p class="text-gray-600 mt-1">Analisis dan rekapitulasi data transaksi RotiKita Bakery.</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('laporan.excel', request()->all()) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg shadow-sm transition-colors text-sm font-medium">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Export Excel
            </a>
            <a href="{{ route('laporan.pdf', request()->all()) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-[#8B5E34] hover:bg-[#6e4928] text-white rounded-lg shadow-sm transition-colors text-sm font-medium">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Export PDF
            </a>
        </div>
    </div>

    <!-- Filters Area -->
    <div class="bg-[#FAF6F0] rounded-xl shadow-sm border border-[#E8DCC4] p-5">
        <form action="{{ route('laporan.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
            <div class="lg:col-span-2 flex gap-4">
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Mulai Tanggal</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full border-gray-300 focus:border-[#8B5E34] focus:ring focus:ring-[#8B5E34] focus:ring-opacity-50 rounded-lg shadow-sm">
                </div>
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Selesai Tanggal</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full border-gray-300 focus:border-[#8B5E34] focus:ring focus:ring-[#8B5E34] focus:ring-opacity-50 rounded-lg shadow-sm">
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Produk</label>
                <select name="item" class="w-full border-gray-300 focus:border-[#8B5E34] focus:ring focus:ring-[#8B5E34] focus:ring-opacity-50 rounded-lg shadow-sm">
                    <option value="">Semua Produk</option>
                    @foreach($items as $i)
                        <option value="{{ $i }}" {{ request('item') == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kasir</label>
                <select name="kasir" class="w-full border-gray-300 focus:border-[#8B5E34] focus:ring focus:ring-[#8B5E34] focus:ring-opacity-50 rounded-lg shadow-sm">
                    <option value="">Semua Kasir</option>
                    @foreach($kasirs as $k)
                        <option value="{{ $k }}" {{ request('kasir') == $k ? 'selected' : '' }}>{{ $k }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Metode Bayar</label>
                <select name="metode_bayar" class="w-full border-gray-300 focus:border-[#8B5E34] focus:ring focus:ring-[#8B5E34] focus:ring-opacity-50 rounded-lg shadow-sm">
                    <option value="">Semua Metode</option>
                    <option value="cash" {{ request('metode_bayar') == 'cash' ? 'selected' : '' }}>Cash</option>
                    <option value="qris" {{ request('metode_bayar') == 'qris' ? 'selected' : '' }}>QRIS</option>
                    <option value="debit" {{ request('metode_bayar') == 'debit' ? 'selected' : '' }}>Debit</option>
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button type="submit" class="w-full bg-[#8B5E34] hover:bg-[#6e4928] text-white px-4 py-2 rounded-lg shadow-sm transition-colors text-sm font-medium">
                    Filter
                </button>
                <a href="{{ route('laporan.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg shadow-sm transition-colors text-sm font-medium flex items-center justify-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 border-l-4 border-l-green-500">
            <p class="text-sm font-medium text-gray-500">Total Pendapatan</p>
            <h3 class="text-2xl font-bold text-gray-900 mt-1">Rp {{ number_format($summary['total_revenue'], 0, ',', '.') }}</h3>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 border-l-4 border-l-blue-500">
            <p class="text-sm font-medium text-gray-500">Total Transaksi</p>
            <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($summary['total_transactions']) }} <span class="text-sm font-normal text-gray-500">struk</span></h3>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 border-l-4 border-l-yellow-500">
            <p class="text-sm font-medium text-gray-500">Total Item Terjual</p>
            <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($summary['total_items']) }} <span class="text-sm font-normal text-gray-500">pcs</span></h3>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 border-l-4 border-l-purple-500">
            <p class="text-sm font-medium text-gray-500">Rata-rata Transaksi (AOV)</p>
            <h3 class="text-2xl font-bold text-gray-900 mt-1">Rp {{ number_format($summary['avg_order_value'], 0, ',', '.') }}</h3>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-[#8B5E34] text-white">
                    <tr>
                        <th class="px-6 py-4 font-medium">Tanggal & Waktu</th>
                        <th class="px-6 py-4 font-medium">ID Struk</th>
                        <th class="px-6 py-4 font-medium">Produk</th>
                        <th class="px-6 py-4 font-medium text-right">Qty</th>
                        <th class="px-6 py-4 font-medium text-right">Harga Satuan</th>
                        <th class="px-6 py-4 font-medium text-right">Subtotal</th>
                        <th class="px-6 py-4 font-medium">Kasir</th>
                        <th class="px-6 py-4 font-medium">Metode</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($transactions as $trx)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-3 whitespace-nowrap">{{ \Carbon\Carbon::parse($trx->date_time)->format('d M Y, H:i') }}</td>
                        <td class="px-6 py-3 font-medium text-gray-900">#{{ $trx->transaction_id }}</td>
                        <td class="px-6 py-3">{{ $trx->item }}</td>
                        <td class="px-6 py-3 text-right">{{ $trx->quantity }}</td>
                        <td class="px-6 py-3 text-right">Rp {{ number_format($trx->harga_satuan, 0, ',', '.') }}</td>
                        <td class="px-6 py-3 text-right font-medium text-gray-900">Rp {{ number_format($trx->subtotal, 0, ',', '.') }}</td>
                        <td class="px-6 py-3">{{ $trx->kasir ?? '-' }}</td>
                        <td class="px-6 py-3">
                            @if(strtolower($trx->metode_bayar) == 'qris')
                                <span class="px-2.5 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">QRIS</span>
                            @elseif(strtolower($trx->metode_bayar) == 'debit')
                                <span class="px-2.5 py-1 bg-purple-100 text-purple-800 rounded-full text-xs font-medium">Debit</span>
                            @else
                                <span class="px-2.5 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">Cash</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                            <svg class="w-12 h-12 mx-auto text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                            Tidak ada data transaksi ditemukan untuk filter ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        @if($transactions->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $transactions->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
