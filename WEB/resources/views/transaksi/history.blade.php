@extends('layouts.app')

@section('title', 'Riwayat Transaksi')

@section('content')
<div class="space-y-8 pb-12">
    <!-- Unified Premium Page Header Card -->
    <div class="relative overflow-hidden p-6 md:p-8 rounded-3xl bg-gradient-to-br from-white/90 via-white/50 to-[#fdfbf7]/30 dark:from-[#111827]/90 dark:via-[#111827]/60 dark:to-slate-900/30 backdrop-blur-xl border border-slate-200/60 dark:border-white/5 shadow-xl shadow-slate-100/40 dark:shadow-none transition-all duration-300">
        <!-- Glowing background orbs for subtle premium aesthetic -->
        <div class="absolute top-0 right-0 -mt-12 -mr-12 w-48 h-48 bg-gradient-to-br from-[#d3a15c]/10 to-[#c58744]/10 dark:from-[#d3a15c]/5 dark:to-[#c58744]/5 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-10 left-1/3 w-36 h-36 bg-emerald-500/5 dark:bg-emerald-500/2 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-start md:justify-between gap-6">
            <!-- Left Info Block -->
            <div class="space-y-4 max-w-3xl">
                <!-- Badges Row -->
                <div class="flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-amber-500/10 dark:bg-amber-500/20 text-amber-700 dark:text-amber-300 border border-amber-500/20 dark:border-amber-500/30">
                        <i class="fas fa-history animate-pulse text-[11px] text-amber-600 dark:text-amber-400"></i>
                        History Logs Active
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-500/10 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/50">
                        <i class="fas fa-database text-[11px] text-emerald-600 dark:text-emerald-400"></i>
                        Total: {{ count($transactions) }} Transaksi
                    </span>
                </div>

                <!-- Main Heading & Title -->
                <div>
                    <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight text-slate-900 dark:text-white">
                        Riwayat & <span class="bg-gradient-to-r from-[#d3a15c] to-[#c58744] bg-clip-text text-transparent">Log Transaksi</span>
                    </h1>
                </div>
            </div>

            <!-- Right Actions Block -->
            <div class="flex flex-col items-stretch md:items-end justify-between self-stretch shrink-0 md:min-w-[180px]">
                <!-- Kembali ke POS Button -->
                <div class="flex flex-col gap-3 w-full">
                    <a href="{{ route('transactions.index') }}" class="group relative w-full flex items-center justify-center px-5 py-3 bg-gradient-to-r from-[#d3a15c] to-[#c58744] hover:from-[#c58744] hover:to-[#a06c30] text-white font-bold rounded-2xl shadow-lg shadow-amber-500/20 dark:shadow-none hover:shadow-xl hover:shadow-amber-500/30 transition-all duration-300 outline-none transform active:scale-[0.98]">
                        <i class="fas fa-cash-register mr-2.5 text-sm group-hover:scale-110 transition-transform"></i>
                        <span class="text-sm tracking-wide">Kembali ke POS</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- FILTER AREA -->
    <form id="filterForm" action="{{ route('transactions.history') }}" method="GET" class="glass-card p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800 transition-all duration-300">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-center">
            <!-- SEARCH -->
            <div class="space-y-1.5">
                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider pl-1 flex justify-between">
                    <span>Cari Produk</span>
                    @if(request('search') || request('date'))
                        <a href="{{ route('transactions.history') }}" class="text-[10px] text-amber-600 dark:text-amber-500 hover:underline capitalize">Reset Saringan</a>
                    @endif
                </label>
                <div class="relative group">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 dark:text-slate-500 group-focus-within:text-amber-500 transition-colors">
                        <i class="fas fa-search text-xs"></i>
                    </span>
                    <input type="text" name="search" id="searchInput" value="{{ request('search') }}" placeholder="Cari nama produk roti..." class="w-full pl-10 pr-12 py-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/5 rounded-xl text-xs text-slate-800 dark:text-white placeholder-slate-400 dark:placeholder-slate-650 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all font-semibold">
                    <button type="submit" class="absolute inset-y-0 right-0 flex items-center pr-4 text-slate-400 hover:text-amber-500 transition-colors">
                        <i class="fas fa-arrow-right text-xs"></i>
                    </button>
                </div>
            </div>

            <!-- FILTER DATE -->
            <div class="space-y-1.5">
                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider pl-1">
                    Filter Tanggal
                </label>
                <input type="date" name="date" id="dateFilter" value="{{ request('date') }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 outline-none transition-all text-xs font-semibold">
            </div>

            <!-- TOTAL PENDAPATAN -->
            <div class="bg-amber-50/50 dark:bg-amber-950/20 border border-amber-200 dark:border-amber-900/50 rounded-2xl p-4 flex flex-col justify-center transition-all duration-300">
                <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest leading-none mb-1">
                    Total Pendapatan Terfilter
                </span>
                <h2 id="grandTotal" class="text-2xl font-black text-amber-600 dark:text-amber-450 mt-1.5 tabular-nums">
                    Rp {{ number_format($grandTotal ?? 0, 0, ',', '.') }}
                </h2>
            </div>
        </div>
    </form>

    <!-- TABLE AREA -->
    <div class="glass-card rounded-2xl shadow-sm overflow-hidden flex flex-col border border-slate-100 dark:border-slate-800 transition-all duration-300">
        <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/30 flex justify-between items-center transition-colors">
            <div>
                <h3 class="font-bold text-slate-900 dark:text-white">Rekaman Log Penjualan</h3>
            </div>
            <span class="px-3 py-1 rounded-lg text-xs text-amber-700 dark:text-amber-400 bg-amber-50 dark:bg-amber-900/20 font-bold border border-amber-200 dark:border-amber-800/50">Database Sync</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-slate-400 dark:text-slate-500 text-[10px] font-bold uppercase tracking-widest border-b border-slate-100 dark:border-slate-800 transition-colors">
                        <th class="px-6 py-4">ID Struk</th>
                        <th class="px-6 py-4">Produk</th>
                        <th class="px-6 py-4 text-right">Qty</th>
                        <th class="px-6 py-4 text-right">Harga</th>
                        <th class="px-6 py-4 text-right">Subtotal</th>
                        <th class="px-6 py-4">Pembayaran</th>
                        <th class="px-6 py-4">Tanggal & Waktu</th>
                    </tr>
                </thead>
                <tbody id="transactionTable" class="divide-y divide-slate-100 dark:divide-slate-800 transition-colors">
                    @foreach($transactions as $trx)
                    <tr class="hover:bg-amber-50/20 dark:hover:bg-slate-850/30 transition-colors transaction-row" data-item="{{ strtolower($trx->item) }}" data-date="{{ \Carbon\Carbon::parse($trx->date_time)->format('Y-m-d') }}" data-subtotal="{{ $trx->subtotal }}">
                        <td class="px-6 py-3.5 font-bold text-slate-900 dark:text-white text-xs">
                            #TRX-{{ $trx->transaction_id }}
                        </td>
                        <td class="px-6 py-3.5 font-semibold text-slate-800 dark:text-slate-300 text-xs">
                            {{ $trx->item }}
                        </td>
                        <td class="px-6 py-3.5 text-right font-medium text-slate-700 dark:text-slate-300 text-xs tabular-nums">
                            {{ $trx->quantity }} Pcs
                        </td>
                        <td class="px-6 py-3.5 text-right text-slate-500 dark:text-slate-400 text-xs tabular-nums">
                            Rp {{ number_format($trx->harga_satuan, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-3.5 text-right font-black text-amber-600 dark:text-amber-400 text-xs tabular-nums">
                            Rp {{ number_format($trx->subtotal, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-3.5 whitespace-nowrap">
                            @if($trx->metode_bayar == 'cash')
                                <span class="px-2.5 py-0.5 bg-emerald-50 dark:bg-emerald-950/30 text-emerald-700 dark:text-emerald-400 rounded-lg text-[10px] font-bold border border-emerald-200/50 dark:border-emerald-800/30">
                                    Cash
                                </span>
                            @elseif($trx->metode_bayar == 'qris')
                                <span class="px-2.5 py-0.5 bg-blue-50 dark:bg-blue-950/30 text-blue-700 dark:text-blue-400 rounded-lg text-[10px] font-bold border border-blue-200/50 dark:border-blue-800/30">
                                    QRIS
                                </span>
                            @else
                                <span class="px-2.5 py-0.5 bg-purple-50 dark:bg-purple-950/30 text-purple-700 dark:text-purple-400 rounded-lg text-[10px] font-bold border border-purple-200/50 dark:border-purple-800/30">
                                    Debit
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-3.5 text-slate-500 dark:text-slate-400 text-xs whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($trx->date_time)->isoFormat('D MMM Y, H:i') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- EMPTY MESSAGE -->
            @if($transactions->isEmpty())
            <div id="emptyMessage" class="text-center py-16 text-slate-400 dark:text-slate-500">
                <i class="fas fa-inbox text-3xl mb-3 block text-slate-200 dark:text-slate-700"></i>
                Data transaksi tidak ditemukan untuk penyaringan filter ini.
            </div>
            @endif
        </div>

        <!-- PAGINATION LINKS -->
        @if($transactions->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50/30 dark:bg-slate-900/30">
            {{ $transactions->links() }}
        </div>
        @endif
    </div>
</div>

<script>
document.getElementById('dateFilter').addEventListener('change', function() {
    document.getElementById('filterForm').submit();
});
</script>
@endsection