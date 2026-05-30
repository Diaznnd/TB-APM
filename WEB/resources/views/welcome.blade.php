@extends('layouts.app')

@section('title', 'Dashboard Utama')

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
                        <i class="fas fa-bread-slice animate-pulse text-[11px] text-amber-600 dark:text-amber-400"></i>
                        System Active
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-500/10 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-300 border border-emerald-500/20 dark:border-emerald-500/30">
                        <i class="fas fa-shield-alt text-[11px] text-emerald-600 dark:text-emerald-400"></i>
                        Security Safe
                    </span>
                </div>

                <!-- Main Heading & Title -->
                <div>
                    <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight text-slate-900 dark:text-white">
                        Selamat Datang, <span class="bg-gradient-to-r from-[#d3a15c] to-[#c58744] bg-clip-text text-transparent">Admin Golden Tulip Bakery</span>
                    </h1>
                </div>

                <!-- Horizontal Pills for Key Insights/Meta -->
                <div class="flex flex-wrap items-center gap-x-5 gap-y-2.5 pt-2 border-t border-slate-200/50 dark:border-slate-800/60 text-xs">
                    <div class="flex items-center gap-1.5 text-slate-500 dark:text-slate-400">
                        <i class="fas fa-user-tie text-amber-500/70 dark:text-amber-400/60 text-[13px]"></i>
                        <span>Role:</span>
                        <strong class="text-slate-700 dark:text-slate-300 font-semibold">Pemilik Toko (Admin)</strong>
                    </div>
                    <div class="h-3.5 w-px bg-slate-300 dark:bg-slate-800 hidden sm:block"></div>
                    <div class="flex items-center gap-1.5 text-slate-500 dark:text-slate-400">
                        <i class="fas fa-server text-amber-500/70 dark:text-amber-400/60 text-[13px]"></i>
                        <span>Status Sistem:</span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded bg-emerald-50 dark:bg-emerald-950/30 text-emerald-700 dark:text-emerald-400 font-medium text-[10px] border border-emerald-200 dark:border-emerald-800/50">
                            Online & Stabil
                        </span>
                    </div>
                </div>
            </div>

            <!-- Right Actions Block -->
            <div class="flex flex-col items-stretch md:items-end justify-between self-stretch shrink-0 md:min-w-[180px]">
                <div class="flex flex-col gap-3 w-full">
                    <a href="/forecasting" class="group relative w-full flex items-center justify-center px-5 py-3 bg-gradient-to-r from-[#d3a15c] to-[#c58744] hover:from-[#c58744] hover:to-[#a06c30] text-white font-bold rounded-2xl shadow-lg shadow-[#d3a15c]/20 dark:shadow-none hover:shadow-xl hover:shadow-[#d3a15c]/30 transition-all duration-300 outline-none transform active:scale-[0.98]">
                        <i class="fas fa-brain mr-2.5 text-sm group-hover:animate-pulse"></i>
                        <span class="text-sm tracking-wide">Buka ML Forecasting</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Stat 1 (Transaksi Hari Ini) -->
        <div class="glass-card p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800 hover:shadow-md hover:scale-[1.01] transition-all duration-300">
            <div class="flex items-start justify-between mb-1">
                <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Transactions Today</p>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400">
                    Hari Ini
                </span>
            </div>
            <h3 class="text-3xl font-black text-slate-900 dark:text-white leading-none mt-2 tabular-nums">
                {{ number_format($totalTransaksi, 0, ',', '.') }}
            </h3>
            <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-1.5 mt-4 overflow-hidden">
                <div class="bg-blue-500 h-1.5 rounded-full" style="width: {{ min(max(abs($changeTransaksi) * 5, 20), 100) }}%"></div>
            </div>
        </div>

        <!-- Stat 2 (Stok Menipis) -->
        @php
            $stockColor = $stokMenipis > 0 ? 'rose' : 'emerald';
        @endphp
        <div class="glass-card p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800 hover:shadow-md hover:scale-[1.01] transition-all duration-300">
            <div class="flex items-start justify-between mb-1">
                <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Low Stock Alert</p>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-{{ $stockColor }}-100 dark:bg-{{ $stockColor }}-900/30 text-{{ $stockColor }}-700 dark:text-{{ $stockColor }}-400">
                    {{ $stokMenipis > 0 ? 'Perlu Restock' : 'Aman' }}
                </span>
            </div>
            <h3 class="text-3xl font-black text-{{ $stockColor }}-600 dark:text-{{ $stockColor }}-400 leading-none mt-2 tabular-nums">
                {{ $stokMenipis }}
            </h3>
            <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-1.5 mt-4 overflow-hidden">
                <div class="bg-{{ $stockColor }}-500 h-1.5 rounded-full" style="width: {{ $stokMenipis > 0 ? min($stokMenipis * 20, 100) : 100 }}%"></div>
            </div>
        </div>

        <!-- Stat 3 (Prediksi Besok / Terbaru) -->
        <div class="glass-card p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800 hover:shadow-md hover:scale-[1.01] transition-all duration-300">
            <div class="flex items-start justify-between mb-1">
                <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">
                    {{ $isFallbackDate ? 'Latest Forecast' : "Tomorrow's Forecast" }}
                </p>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400">
                    {{ $isFallbackDate ? \Carbon\Carbon::parse($prediksiTanggal)->translatedFormat('d M') : 'Esok Hari' }}
                </span>
            </div>
            <h3 class="text-3xl font-black text-amber-600 dark:text-amber-400 leading-none mt-2 tabular-nums">
                {{ number_format($prediksiTotal, 0, ',', '.') }}
            </h3>
            <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-1.5 mt-4 overflow-hidden">
                <div class="bg-amber-500 h-1.5 rounded-full" style="width: 65%"></div>
            </div>
        </div>

        <!-- Stat 4 (Akurasi Model ML) -->
        <div class="glass-card p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800 hover:shadow-md hover:scale-[1.01] transition-all duration-300">
            <div class="flex items-start justify-between mb-1">
                <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">ML Model Status</p>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400">
                    Aktif
                </span>
            </div>
            <h3 class="text-3xl font-black text-emerald-600 dark:text-emerald-400 leading-none mt-2 tabular-nums">
                {{ floatval($modelAkurasi) * 100 }}%
            </h3>
            <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-1.5 mt-4 overflow-hidden">
                <div class="bg-emerald-500 h-1.5 rounded-full" style="width: {{ floatval($modelAkurasi) * 100 }}%"></div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Recent Transactions -->
        <div class="lg:col-span-2 glass-card rounded-2xl shadow-sm overflow-hidden flex flex-col border border-slate-100 dark:border-slate-800 transition-all duration-300">
            <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/30 flex justify-between items-center transition-colors">
                <div>
                    <h3 class="font-bold text-slate-900 dark:text-white">Transaksi Terbaru Hari Ini</h3>
                </div>
                <a href="/transactions/history" class="px-3 py-1 rounded-lg text-xs text-amber-700 dark:text-amber-400 bg-amber-50 dark:bg-amber-900/20 font-bold border border-amber-200 dark:border-amber-800/50 hover:bg-amber-100 dark:hover:bg-amber-900/40 transition-all">Lihat Semua</a>
            </div>
            <div class="p-6 space-y-6">
                @if(count($transaksiTerbaru) > 0)
                    @foreach($transaksiTerbaru as $tx)
                        <div class="flex gap-4 relative {{ !$loop->last ? 'after:absolute after:left-[19px] after:top-[44px] after:bottom-[-24px] after:w-px after:bg-slate-200 dark:after:bg-slate-800' : '' }}">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 z-10 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border border-blue-100/50 dark:border-white/5">
                                <i class="fas fa-shopping-cart text-sm"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex justify-between items-start gap-4">
                                    <h4 class="font-bold text-slate-800 dark:text-white text-sm truncate">Transaksi #TRX-{{ $tx['transaction_id'] }}</h4>
                                    <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider whitespace-nowrap shrink-0">
                                        {{ \Carbon\Carbon::parse($tx['date_time'])->diffForHumans() }}
                                    </span>
                                </div>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 leading-relaxed">
                                    Pembelian <strong class="text-slate-700 dark:text-slate-200 font-semibold">{{ $tx['quantity'] }}x {{ $tx['item'] }}</strong> (Rp {{ number_format($tx['subtotal'], 0, ',', '.') }}) oleh Kasir {{ $tx['kasir'] ?? '-' }} via {{ strtoupper($tx['metode_bayar'] ?? '-') }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-12 text-slate-400 dark:text-slate-500">
                        <i class="fas fa-cash-register text-3xl mb-3 block text-slate-300 dark:text-slate-700"></i>
                        <p class="text-sm">Belum ada transaksi hari ini.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Right Column Widgets -->
        <div class="space-y-8">
            <!-- Products Chart Preview -->
            <div class="glass-card p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800 transition-all duration-300">
                <div class="mb-5">
                    <h3 class="font-bold text-slate-900 dark:text-white">Top Produk Hari Ini</h3>
                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">Produk terlaris berdasarkan transaksi hari ini</p>
                </div>
                <div class="space-y-4">
                    @if(count($topProduk) > 0)
                        @foreach($topProduk as $p)
                            <div class="space-y-1.5">
                                <div class="flex justify-between text-xs font-bold uppercase tracking-wider">
                                    <span class="text-slate-600 dark:text-slate-400">{{ $p['item'] }}</span>
                                    <span class="text-slate-900 dark:text-white">{{ $p['qty'] }} Pcs</span>
                                </div>
                                <div class="h-2 w-full bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-[#d3a15c] to-[#c58744] rounded-full" style="width: {{ $p['pct'] }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-8 text-slate-400 dark:text-slate-500">
                            <i class="fas fa-bread-slice text-2xl mb-2 block text-slate-300 dark:text-slate-700"></i>
                            <p class="text-xs">Belum ada data penjualan produk hari ini.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Promotion Card -->
            <div class="relative overflow-hidden p-6 rounded-2xl bg-gradient-to-br from-[#6d462d] to-[#4a2e1b] dark:from-[#1e1b18] dark:to-[#0f0e0d] border border-[#865534]/30 dark:border-white/5 shadow-lg group transition-all duration-300">
                <div class="absolute -right-8 -top-8 w-32 h-32 bg-amber-500/20 dark:bg-amber-500/10 rounded-full blur-2xl group-hover:scale-125 transition-transform duration-500"></div>
                <div class="relative z-10">
                    <h3 class="text-[#fdfbf7] dark:text-white font-bold text-base leading-tight mb-2">Optimalkan Penjualan dengan AI</h3>
                    <p class="text-[#f2e7d0]/80 dark:text-slate-400 text-xs leading-relaxed mb-5">Sistem ML kami menganalisis pola pembelian pelanggan untuk memberikan rekomendasi stok yang presisi.</p>
                    <a href="/forecasting" class="text-amber-300 dark:text-amber-500 text-xs font-black uppercase tracking-widest flex items-center gap-2 group-hover:gap-3 transition-all duration-300">
                        Pelajari Lebih Lanjut
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection