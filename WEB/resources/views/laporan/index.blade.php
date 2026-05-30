@extends('layouts.app')

@section('title', 'Laporan Penjualan')

@section('content')
<div class="space-y-8 pb-12">
    <!-- Unified Premium Page Header Card -->
    <div class="relative overflow-hidden p-6 md:p-8 rounded-3xl bg-gradient-to-br from-white/90 via-white/50 to-[#fdfbf7]/30 dark:from-[#111827]/90 dark:via-[#111827]/60 dark:to-slate-900/30 backdrop-blur-xl border border-slate-200/60 dark:border-white/5 shadow-xl shadow-slate-100/40 dark:shadow-none transition-all duration-300">
        <!-- Glowing background orbs for subtle premium aesthetic -->
        <div class="absolute top-0 right-0 -mt-12 -mr-12 w-48 h-48 bg-gradient-to-br from-[#d3a15c]/10 to-[#c58744]/10 dark:from-[#d3a15c]/5 dark:to-[#c58744]/5 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-10 left-1/3 w-36 h-36 bg-amber-500/5 dark:bg-amber-500/2 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-start md:justify-between gap-6">
            <!-- Left Info Block -->
            <div class="space-y-4 max-w-3xl">
                <!-- Badges Row -->
                <div class="flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-amber-500/10 dark:bg-amber-500/20 text-amber-700 dark:text-amber-300 border border-amber-500/20 dark:border-amber-500/30">
                        <i class="fas fa-file-invoice animate-pulse text-[11px] text-amber-600 dark:text-amber-400"></i>
                        Reporting System Active
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-amber-500/10 dark:bg-amber-500/20 text-amber-700 dark:text-amber-300 border border-amber-500/20 dark:border-amber-500/30">
                        <i class="fas fa-calendar-alt text-[11px] text-amber-600 dark:text-amber-400"></i>
                        Rekapitulasi Toko
                    </span>
                </div>

                <!-- Main Heading & Title -->
                <div>
                    <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight text-slate-900 dark:text-white">
                        Laporan & <span class="bg-gradient-to-r from-[#d3a15c] to-[#c58744] bg-clip-text text-transparent">Analisis Penjualan</span>
                    </h1>
                </div>
            </div>

            <!-- Right Actions Block -->
            <div class="flex flex-col items-stretch md:items-end justify-between self-stretch shrink-0 md:min-w-[280px]">
                <!-- Export Buttons Row -->
                <div class="flex flex-col sm:flex-row gap-3 w-full">
                    <!-- Excel -->
                    <a href="{{ route('laporan.excel', request()->all()) }}" class="group relative flex-1 flex items-center justify-center px-4 py-3 bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 dark:from-emerald-600 dark:to-teal-600 text-white font-bold rounded-2xl shadow-lg shadow-emerald-500/20 dark:shadow-none hover:shadow-xl hover:shadow-emerald-500/30 transition-all duration-300 outline-none text-xs transform active:scale-[0.98]">
                        <i class="fas fa-file-excel mr-2 text-sm group-hover:scale-110 transition-transform"></i>
                        <span>Export Excel</span>
                    </a>
                    <!-- PDF -->
                    <a href="{{ route('laporan.pdf', request()->all()) }}" target="_blank" class="group relative flex-1 flex items-center justify-center px-4 py-3 bg-gradient-to-r from-rose-500 to-orange-500 hover:from-rose-600 hover:to-orange-600 dark:from-rose-600 dark:to-orange-600 text-white font-bold rounded-2xl shadow-lg shadow-rose-500/20 dark:shadow-none hover:shadow-xl hover:shadow-rose-500/30 transition-all duration-300 outline-none text-xs transform active:scale-[0.98]">
                        <i class="fas fa-file-pdf mr-2 text-sm group-hover:scale-110 transition-transform"></i>
                        <span>Export PDF</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Area -->
    <div class="glass-card p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800 transition-all duration-300">
        <form action="{{ route('laporan.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
            <div class="lg:col-span-2 flex gap-4">
                <div class="w-1/2 space-y-1.5">
                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider pl-1">Mulai Tanggal</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 focus:ring-2 focus:ring-[#d3a15c]/20 focus:border-[#d3a15c] outline-none transition-all text-xs font-semibold">
                </div>
                <div class="w-1/2 space-y-1.5">
                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider pl-1">Selesai Tanggal</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 focus:ring-2 focus:ring-[#d3a15c]/20 focus:border-[#d3a15c] outline-none transition-all text-xs font-semibold">
                </div>
            </div>
            
            <div class="space-y-1.5">
                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider pl-1">Produk</label>
                <select name="item" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 focus:ring-2 focus:ring-[#d3a15c]/20 focus:border-[#d3a15c] outline-none transition-all text-xs font-semibold">
                    <option value="">Semua Produk</option>
                    @foreach($items as $i)
                        <option value="{{ $i }}" {{ request('item') == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endforeach
                </select>
            </div>

            <div class="space-y-1.5">
                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider pl-1">Kasir</label>
                <select name="kasir" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 focus:ring-2 focus:ring-[#d3a15c]/20 focus:border-[#d3a15c] outline-none transition-all text-xs font-semibold">
                    <option value="">Semua Kasir</option>
                    @foreach($kasirs as $k)
                        <option value="{{ $k }}" {{ request('kasir') == $k ? 'selected' : '' }}>{{ $k }}</option>
                    @endforeach
                </select>
            </div>

            <div class="space-y-1.5">
                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider pl-1">Metode Bayar</label>
                <select name="metode_bayar" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 focus:ring-2 focus:ring-[#d3a15c]/20 focus:border-[#d3a15c] outline-none transition-all text-xs font-semibold">
                    <option value="">Semua Metode</option>
                    <option value="cash" {{ request('metode_bayar') == 'cash' ? 'selected' : '' }}>Cash</option>
                    <option value="qris" {{ request('metode_bayar') == 'qris' ? 'selected' : '' }}>QRIS</option>
                    <option value="debit" {{ request('metode_bayar') == 'debit' ? 'selected' : '' }}>Debit</option>
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 bg-gradient-to-r from-[#d3a15c] to-[#c58744] hover:from-[#c58744] hover:to-[#a06c30] text-white px-4 py-2.5 rounded-xl shadow-md shadow-[#d3a15c]/10 font-bold transition-all text-xs outline-none">
                    Filter
                </button>
                <a href="{{ route('laporan.index') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-xl font-bold transition-all text-xs flex items-center justify-center border border-slate-200/50 dark:border-white/5">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Pendapatan -->
        <div class="glass-card p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800 hover:shadow-md hover:scale-[1.01] transition-all duration-300">
            <div class="flex items-start justify-between mb-1">
                <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Total Pendapatan</p>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400">
                    Revenue
                </span>
            </div>
            <h3 class="text-3xl font-black text-emerald-600 dark:text-emerald-400 leading-none mt-2 tabular-nums">
                Rp {{ number_format($summary['total_revenue'], 0, ',', '.') }}
            </h3>
        </div>

        <!-- Transaksi -->
        <div class="glass-card p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800 hover:shadow-md hover:scale-[1.01] transition-all duration-300">
            <div class="flex items-start justify-between mb-1">
                <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Total Transaksi</p>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400">
                    Transactions
                </span>
            </div>
            <div class="flex items-baseline gap-1.5 mt-2">
                <h3 class="text-3xl font-black text-blue-600 dark:text-blue-400 leading-none tabular-nums">
                    {{ number_format($summary['total_transactions']) }}
                </h3>
                <span class="text-xs font-bold text-blue-500/60">struk</span>
            </div>
        </div>

        <!-- Item Terjual -->
        <div class="glass-card p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800 hover:shadow-md hover:scale-[1.01] transition-all duration-300">
            <div class="flex items-start justify-between mb-1">
                <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Total Item Terjual</p>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400">
                    Items Sold
                </span>
            </div>
            <div class="flex items-baseline gap-1.5 mt-2">
                <h3 class="text-3xl font-black text-amber-600 dark:text-amber-450 leading-none tabular-nums">
                    {{ number_format($summary['total_items']) }}
                </h3>
                <span class="text-xs font-bold text-amber-500/60">pcs</span>
            </div>
        </div>

        <!-- Rata-rata Transaksi (AOV) -->
        <div class="glass-card p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800 hover:shadow-md hover:scale-[1.01] transition-all duration-300">
            <div class="flex items-start justify-between mb-1">
                <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Average Order Value</p>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400">
                    AOV
                </span>
            </div>
            <h3 class="text-3xl font-black text-amber-600 dark:text-amber-400 leading-none mt-2 tabular-nums">
                Rp {{ number_format($summary['avg_order_value'], 0, ',', '.') }}
            </h3>
        </div>
    </div>

    <!-- Data Table Card -->
    <div class="glass-card rounded-2xl shadow-sm overflow-hidden flex flex-col border border-slate-100 dark:border-slate-800 transition-all duration-300">
        <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/30 flex justify-between items-center transition-colors">
            <div>
                <h3 class="font-bold text-slate-900 dark:text-white">Rincian Transaksi Penjualan</h3>
            </div>
            <span class="px-3 py-1 rounded-lg text-xs text-amber-700 dark:text-amber-400 bg-amber-50 dark:bg-amber-900/20 font-bold border border-amber-200 dark:border-amber-800/50">Data Saring</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-650">
                <thead>
                    <tr class="text-slate-400 dark:text-slate-500 text-[10px] font-bold uppercase tracking-widest border-b border-slate-100 dark:border-slate-800 transition-colors">
                        <th class="px-6 py-4">Tanggal & Waktu</th>
                        <th class="px-6 py-4">ID Struk</th>
                        <th class="px-6 py-4">Produk</th>
                        <th class="px-6 py-4 text-right">Qty</th>
                        <th class="px-6 py-4 text-right">Harga Satuan</th>
                        <th class="px-6 py-4 text-right">Subtotal</th>
                        <th class="px-6 py-4">Kasir</th>
                        <th class="px-6 py-4">Metode</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 transition-colors">
                    @forelse($transactions as $trx)
                    <tr class="hover:bg-amber-50/20 dark:hover:bg-slate-850/30 transition-colors">
                        <td class="px-6 py-3.5 whitespace-nowrap text-slate-550 dark:text-slate-400 text-xs">
                            {{ \Carbon\Carbon::parse($trx->date_time)->isoFormat('D MMM Y, H:i') }}
                        </td>
                        <td class="px-6 py-3.5 font-bold text-slate-900 dark:text-white text-xs">
                            #TRX-{{ $trx->transaction_id }}
                        </td>
                        <td class="px-6 py-3.5 font-semibold text-slate-800 dark:text-slate-300 text-xs">
                            {{ $trx->item }}
                        </td>
                        <td class="px-6 py-3.5 text-right font-medium text-slate-700 dark:text-slate-300 text-xs tabular-nums">
                            {{ $trx->quantity }}
                        </td>
                        <td class="px-6 py-3.5 text-right text-slate-500 dark:text-slate-400 text-xs tabular-nums">
                            Rp {{ number_format($trx->harga_satuan, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-3.5 text-right font-black text-slate-900 dark:text-white text-xs tabular-nums">
                            Rp {{ number_format($trx->subtotal, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-3.5 text-slate-600 dark:text-slate-400 text-xs">
                            {{ $trx->kasir ?? '-' }}
                        </td>
                        <td class="px-6 py-3.5 whitespace-nowrap">
                            @if(strtolower($trx->metode_bayar) == 'qris')
                                <span class="px-2.5 py-0.5 bg-blue-50 dark:bg-blue-950/30 text-blue-700 dark:text-blue-400 rounded-lg text-[10px] font-bold border border-blue-200/50 dark:border-blue-800/30">QRIS</span>
                            @elseif(strtolower($trx->metode_bayar) == 'debit')
                                <span class="px-2.5 py-0.5 bg-purple-50 dark:bg-purple-950/30 text-purple-700 dark:text-purple-400 rounded-lg text-[10px] font-bold border border-purple-200/50 dark:border-purple-800/30">Debit</span>
                            @else
                                <span class="px-2.5 py-0.5 bg-emerald-50 dark:bg-emerald-950/30 text-emerald-700 dark:text-emerald-400 rounded-lg text-[10px] font-bold border border-emerald-200/50 dark:border-emerald-800/30">Cash</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-16 text-center text-slate-400 dark:text-slate-500">
                            <i class="fas fa-inbox text-3xl mb-3 block text-slate-200 dark:text-slate-700"></i>
                            Tidak ada data transaksi ditemukan untuk saringan filter ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        @if($transactions->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-800 transition-colors">
            {{ $transactions->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

