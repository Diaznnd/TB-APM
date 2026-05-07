@extends('layouts.app')

@section('title', 'Dashboard Utama')

@section('content')
<div class="space-y-8 pb-12">
    <!-- Welcome Section -->
    <div class="p-8 rounded-2xl shadow-sm border border-[#e8d2ac] dark:border-slate-800 bg-gradient-to-r from-[#fdfbf7] to-[#f9f5ec] dark:from-slate-900 dark:to-slate-950 transition-all">
        <div class="flex flex-col lg:flex-row items-center gap-8">
            <div class="w-24 h-24 bg-[#f2e7d0] dark:bg-amber-900/30 rounded-2xl flex items-center justify-center text-[#c58744] dark:text-amber-500 text-4xl shadow-inner shrink-0 border border-[#e8d2ac] dark:border-transparent">
                <i class="fas fa-bread-slice"></i>
            </div>
            <div>
                <h1 class="text-3xl font-black text-[#6d462d] dark:text-white tracking-tight">Selamat Datang, Admin!</h1>
                <p class="text-[#865534] dark:text-slate-400 mt-2 text-lg">Panel manajemen RotiKita siap membantu Anda mengelola toko dan memantau prediksi penjualan hari ini.</p>
            </div>
            <div class="lg:ml-auto flex gap-3">
                <a href="/forecasting" class="px-6 py-3 bg-[#c58744] hover:bg-[#a56a39] dark:bg-amber-600 dark:hover:bg-amber-700 text-white font-bold rounded-xl shadow-lg shadow-[#c58744]/20 transition-all flex items-center gap-2">
                    <i class="fas fa-brain"></i>
                    Buka ML Forecasting
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Stat 1 (Transaksi) -->
        <div class="bg-white dark:bg-slate-900/80 p-6 rounded-2xl shadow-sm border border-[#e8d2ac] dark:border-slate-800 hover:scale-[1.02] transition-all">
            <div class="flex items-center gap-4 mb-4">
                <div class="p-3 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 rounded-lg border border-blue-100 dark:border-transparent">
                    <i class="fas fa-cash-register"></i>
                </div>
                <span class="text-[10px] font-bold text-[#a56a39] dark:text-slate-500 uppercase tracking-widest">Transaksi Hari Ini</span>
            </div>
            <h3 class="text-3xl font-black text-[#6d462d] dark:text-white">{{ number_format($totalTransaksi, 0, ',', '.') }}</h3>
            <p class="text-xs {{ $changeTransaksi >= 0 ? 'text-emerald-600 dark:text-emerald-500' : 'text-rose-600 dark:text-rose-500' }} mt-2 font-bold flex items-center gap-1">
                <i class="fas {{ $changeTransaksi >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }}"></i> {{ abs($changeTransaksi) }}% dari kemarin
            </p>
        </div>

        <!-- Stat 2 (Stok) -->
        <div class="bg-white dark:bg-slate-900/80 p-6 rounded-2xl shadow-sm border border-[#e8d2ac] dark:border-slate-800 hover:scale-[1.02] transition-all">
            <div class="flex items-center gap-4 mb-4">
                <div class="p-3 bg-purple-50 dark:bg-purple-900/20 text-purple-600 dark:text-purple-400 rounded-lg border border-purple-100 dark:border-transparent">
                    <i class="fas fa-boxes"></i>
                </div>
                <span class="text-[10px] font-bold text-[#a56a39] dark:text-slate-500 uppercase tracking-widest">Stok Menipis</span>
            </div>
            <h3 class="text-3xl font-black text-[#6d462d] dark:text-white">{{ $stokMenipis }}</h3>
            @if($stokMenipis > 0)
            <p class="text-xs text-rose-600 dark:text-rose-500 mt-2 font-bold flex items-center gap-1">
                <i class="fas fa-exclamation-triangle"></i> Perlu restock
            </p>
            @else
            <p class="text-xs text-[#a56a39] dark:text-slate-400 mt-2 font-bold flex items-center gap-1">
                <i class="fas fa-check"></i> Stok aman
            </p>
            @endif
        </div>

        <!-- Stat 3 (Prediksi) -->
        <div class="bg-white dark:bg-slate-900/80 p-6 rounded-2xl shadow-sm border border-[#e8d2ac] dark:border-slate-800 hover:scale-[1.02] transition-all">
            <div class="flex items-center gap-4 mb-4">
                <div class="p-3 bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-500 rounded-lg border border-amber-100 dark:border-transparent">
                    <i class="fas fa-chart-line"></i>
                </div>
                <span class="text-[10px] font-bold text-[#a56a39] dark:text-slate-500 uppercase tracking-widest">Prediksi Besok</span>
            </div>
            <h3 class="text-3xl font-black text-[#6d462d] dark:text-white">{{ number_format($prediksiTotal, 0, ',', '.') }}</h3>
            <p class="text-xs text-[#865534] dark:text-slate-400 mt-2 font-medium">Estimasi total unit terjual</p>
        </div>

        <!-- Stat 4 (Model ML) -->
        <div class="bg-white dark:bg-slate-900/80 p-6 rounded-2xl shadow-sm border border-[#e8d2ac] dark:border-slate-800 hover:scale-[1.02] transition-all">
            <div class="flex items-center gap-4 mb-4">
                <div class="p-3 bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 rounded-lg border border-emerald-100 dark:border-transparent">
                    <i class="fas fa-check-circle"></i>
                </div>
                <span class="text-[10px] font-bold text-[#a56a39] dark:text-slate-500 uppercase tracking-widest">Model ML</span>
            </div>
            <h3 class="text-3xl font-black text-[#6d462d] dark:text-white">Aktif</h3>
            <p class="text-xs text-emerald-600 dark:text-emerald-500 mt-2 font-bold flex items-center gap-1">
                <i class="fas fa-shield-alt"></i> Akurasi {{ floatval($modelAkurasi) * 100 }}%
            </p>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Recent Activity -->
        <div class="lg:col-span-2 bg-white dark:bg-slate-900/80 rounded-2xl shadow-sm border border-[#e8d2ac] dark:border-slate-800 overflow-hidden flex flex-col">
            <div class="px-8 py-5 border-b border-[#e8d2ac] dark:border-slate-800 bg-[#f9f5ec] dark:bg-slate-800/30 flex justify-between items-center">
                <h3 class="font-bold text-[#6d462d] dark:text-white text-lg">Transaksi Terbaru Hari Ini</h3>
                <a href="/transaksi" class="text-[#c58744] dark:text-amber-500 text-xs font-black uppercase tracking-widest hover:underline">Lihat Semua</a>
            </div>
            <div class="p-8 space-y-6">
                @if(count($transaksiTerbaru) > 0)
                    @foreach($transaksiTerbaru as $tx)
                        <div class="flex gap-5 relative {{ !$loop->last ? 'after:absolute after:left-[19px] after:top-[44px] after:bottom-[-24px] after:w-px after:bg-[#f2e7d0] dark:after:bg-slate-800' : '' }}">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0 z-10 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border border-blue-100 dark:border-transparent">
                                <i class="fas fa-shopping-cart text-sm"></i>
                            </div>
                            <div class="flex-1">
                                <div class="flex justify-between items-start">
                                    <h4 class="font-bold text-[#6d462d] dark:text-white">Transaksi #TRX-{{ $tx['transaction_id'] }}</h4>
                                    <span class="text-[10px] font-bold text-[#a56a39] dark:text-slate-500 uppercase tracking-wider whitespace-nowrap">
                                        {{ \Carbon\Carbon::parse($tx['date_time'])->diffForHumans() }}
                                    </span>
                                </div>
                                <p class="text-sm text-[#865534] dark:text-slate-400 mt-1 leading-relaxed">
                                    Pembelian {{ $tx['quantity'] }}x {{ $tx['item'] }} (Rp {{ number_format($tx['subtotal'], 0, ',', '.') }}) oleh Kasir {{ $tx['kasir'] ?? '-' }} via {{ strtoupper($tx['metode_bayar'] ?? '-') }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-8">
                        <p class="text-[#865534] dark:text-slate-500">Belum ada transaksi hari ini.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Right Column Widgets -->
        <div class="space-y-8">
            <!-- Products Chart Preview -->
            <div class="bg-white dark:bg-slate-900/80 p-8 rounded-2xl shadow-sm border border-[#e8d2ac] dark:border-slate-800">
                <h3 class="font-bold text-[#6d462d] dark:text-white mb-6">Top Produk Hari Ini</h3>
                <div class="space-y-5">
                    @if(count($topProduk) > 0)
                        @foreach($topProduk as $p)
                            <div class="space-y-2">
                                <div class="flex justify-between text-xs font-bold uppercase tracking-wider">
                                    <span class="text-[#865534] dark:text-slate-400">{{ $p['item'] }}</span>
                                    <span class="text-[#6d462d] dark:text-white">{{ $p['qty'] }} Pcs</span>
                                </div>
                                <div class="h-2 w-full bg-[#f2e7d0] dark:bg-slate-800 rounded-full overflow-hidden">
                                    <div class="h-full bg-[#c58744] dark:bg-amber-500 rounded-full" style="width: {{ $p['pct'] }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-sm text-[#865534] dark:text-slate-500 text-center">Belum ada data penjualan produk hari ini.</p>
                    @endif
                </div>
            </div>

            <!-- Promotion Card -->
            <div class="bg-[#6d462d] dark:bg-[#111827] p-8 rounded-2xl shadow-xl border border-[#865534] dark:border-white/5 relative overflow-hidden group">
                <div class="absolute -right-8 -top-8 w-32 h-32 bg-[#c58744]/20 dark:bg-amber-500/10 rounded-full blur-3xl group-hover:bg-[#c58744]/40 dark:group-hover:bg-amber-500/20 transition-all duration-500"></div>
                <div class="relative z-10">
                    <h3 class="text-[#fdfbf7] dark:text-white font-bold text-lg leading-tight mb-2">Optimalkan Penjualan dengan AI</h3>
                    <p class="text-[#f2e7d0] dark:text-slate-400 text-sm leading-relaxed mb-6">Sistem ML kami menganalisis pola pembelian pelanggan untuk memberikan rekomendasi stok yang presisi.</p>
                    <a href="/forecasting" class="text-[#e8d2ac] dark:text-amber-500 text-xs font-black uppercase tracking-widest flex items-center gap-2 group-hover:gap-3 transition-all">
                        Pelajari Lebih Lanjut
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection