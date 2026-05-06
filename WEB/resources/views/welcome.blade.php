@extends('layouts.app')

@section('title', 'Dashboard Utama')

@section('content')
<div class="space-y-8 pb-12">
    <!-- Welcome Section -->
    <div class="glass-card p-8 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800 bg-gradient-to-r from-white to-slate-50 dark:from-slate-900 dark:to-slate-950 transition-all">
        <div class="flex flex-col lg:flex-row items-center gap-8">
            <div class="w-24 h-24 bg-amber-100 dark:bg-amber-900/30 rounded-2xl flex items-center justify-center text-amber-600 dark:text-amber-500 text-4xl shadow-inner shrink-0">
                <i class="fas fa-bread-slice"></i>
            </div>
            <div>
                <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Selamat Datang, Admin!</h1>
                <p class="text-slate-500 dark:text-slate-400 mt-2 text-lg">Panel manajemen RotiKita siap membantu Anda mengelola toko dan memantau prediksi penjualan hari ini.</p>
            </div>
            <div class="lg:ml-auto flex gap-3">
                <a href="/forecasting" class="px-6 py-3 bg-amber-600 hover:bg-amber-700 text-white font-bold rounded-xl shadow-lg shadow-amber-200/20 transition-all flex items-center gap-2">
                    <i class="fas fa-brain"></i>
                    Buka ML Forecasting
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Stat 1 -->
        <div class="glass-card p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800 hover:scale-[1.02] transition-all">
            <div class="flex items-center gap-4 mb-4">
                <div class="p-3 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 rounded-lg">
                    <i class="fas fa-cash-register"></i>
                </div>
                <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Transaksi Hari Ini</span>
            </div>
            <h3 class="text-3xl font-black text-slate-900 dark:text-white">42</h3>
            <p class="text-xs text-emerald-500 mt-2 font-bold flex items-center gap-1">
                <i class="fas fa-arrow-up"></i> 12% dari kemarin
            </p>
        </div>

        <!-- Stat 2 -->
        <div class="glass-card p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800 hover:scale-[1.02] transition-all">
            <div class="flex items-center gap-4 mb-4">
                <div class="p-3 bg-purple-50 dark:bg-purple-900/20 text-purple-600 dark:text-purple-400 rounded-lg">
                    <i class="fas fa-boxes"></i>
                </div>
                <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Stok Menipis</span>
            </div>
            <h3 class="text-3xl font-black text-slate-900 dark:text-white">5</h3>
            <p class="text-xs text-rose-500 mt-2 font-bold flex items-center gap-1">
                <i class="fas fa-exclamation-triangle"></i> Perlu restock
            </p>
        </div>

        <!-- Stat 3 -->
        <div class="glass-card p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800 hover:scale-[1.02] transition-all">
            <div class="flex items-center gap-4 mb-4">
                <div class="p-3 bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-500 rounded-lg">
                    <i class="fas fa-chart-line"></i>
                </div>
                <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Prediksi Besok</span>
            </div>
            <h3 class="text-3xl font-black text-slate-900 dark:text-white">156</h3>
            <p class="text-xs text-slate-400 dark:text-slate-500 mt-2 font-medium">Estimasi total unit</p>
        </div>

        <!-- Stat 4 -->
        <div class="glass-card p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800 hover:scale-[1.02] transition-all">
            <div class="flex items-center gap-4 mb-4">
                <div class="p-3 bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 rounded-lg">
                    <i class="fas fa-check-circle"></i>
                </div>
                <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Model ML</span>
            </div>
            <h3 class="text-3xl font-black text-slate-900 dark:text-white">Aktif</h3>
            <p class="text-xs text-emerald-500 mt-2 font-bold flex items-center gap-1">
                <i class="fas fa-shield-alt"></i> Akurasi 92.3%
            </p>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Recent Activity -->
        <div class="lg:col-span-2 glass-card rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800 overflow-hidden flex flex-col">
            <div class="px-8 py-5 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/30 flex justify-between items-center">
                <h3 class="font-bold text-slate-900 dark:text-white text-lg">Aktivitas Terbaru</h3>
                <button class="text-amber-600 dark:text-amber-500 text-xs font-black uppercase tracking-widest hover:underline">Lihat Semua</button>
            </div>
            <div class="p-8 space-y-6">
                @php
                    $activities = [
                        ['type' => 'sale', 'title' => 'Transaksi Baru #TRX-942', 'time' => '5 menit yang lalu', 'desc' => 'Pembelian 5x Roti Tawar, 2x Croissant'],
                        ['type' => 'ml', 'title' => 'Model ML Diperbarui', 'time' => '2 jam yang lalu', 'desc' => 'Pelatihan ulang harian selesai dengan R² score 0.923'],
                        ['type' => 'stock', 'title' => 'Restock Selesai', 'time' => '5 jam yang lalu', 'desc' => 'Penerimaan bahan baku tepung terigu 50kg'],
                        ['type' => 'sale', 'title' => 'Transaksi Baru #TRX-941', 'time' => '6 jam yang lalu', 'desc' => 'Pembelian 12x Donat Cokelat']
                    ];
                @endphp

                @foreach($activities as $activity)
                    <div class="flex gap-5 relative {{ !$loop->last ? 'after:absolute after:left-[19px] after:top-[44px] after:bottom-[-24px] after:w-px after:bg-slate-100 dark:after:bg-slate-800' : '' }}">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0 z-10 
                            {{ $activity['type'] == 'sale' ? 'bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400' : '' }}
                            {{ $activity['type'] == 'ml' ? 'bg-amber-100 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400' : '' }}
                            {{ $activity['type'] == 'stock' ? 'bg-emerald-100 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400' : '' }}
                        ">
                            <i class="fas {{ $activity['type'] == 'sale' ? 'fa-shopping-cart' : ($activity['type'] == 'ml' ? 'fa-brain' : 'fa-box') }} text-sm"></i>
                        </div>
                        <div class="flex-1">
                            <div class="flex justify-between items-start">
                                <h4 class="font-bold text-slate-900 dark:text-white">{{ $activity['title'] }}</h4>
                                <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider whitespace-nowrap">{{ $activity['time'] }}</span>
                            </div>
                            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1 leading-relaxed">{{ $activity['desc'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Right Column Widgets -->
        <div class="space-y-8">
            <!-- Products Chart Preview -->
            <div class="glass-card p-8 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800">
                <h3 class="font-bold text-slate-900 dark:text-white mb-6">Top Produk Hari Ini</h3>
                <div class="space-y-5">
                    @php
                        $products = [
                            ['name' => 'Roti Tawar', 'sales' => 85, 'color' => 'bg-amber-500'],
                            ['name' => 'Donat Cokelat', 'sales' => 72, 'color' => 'bg-rose-500'],
                            ['name' => 'Croissant', 'sales' => 45, 'color' => 'bg-blue-500'],
                            ['name' => 'Baguette', 'sales' => 38, 'color' => 'bg-emerald-500']
                        ];
                    @endphp

                    @foreach($products as $p)
                        <div class="space-y-2">
                            <div class="flex justify-between text-xs font-bold uppercase tracking-wider">
                                <span class="text-slate-500 dark:text-slate-400">{{ $p['name'] }}</span>
                                <span class="text-slate-900 dark:text-white">{{ $p['sales'] }} Pcs</span>
                            </div>
                            <div class="h-2 w-full bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                                <div class="h-full {{ $p['color'] }} rounded-full" style="width: {{ ($p['sales'] / 100) * 100 }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Promotion Card -->
            <div class="bg-[#111827] p-8 rounded-2xl shadow-xl border border-white/5 relative overflow-hidden group">
                <div class="absolute -right-8 -top-8 w-32 h-32 bg-amber-500/10 rounded-full blur-3xl group-hover:bg-amber-500/20 transition-all duration-500"></div>
                <div class="relative z-10">
                    <h3 class="text-white font-bold text-lg leading-tight mb-2">Optimalkan Penjualan dengan AI</h3>
                    <p class="text-slate-400 text-sm leading-relaxed mb-6">Sistem ML kami menganalisis pola pembelian pelanggan untuk memberikan rekomendasi stok yang presisi.</p>
                    <a href="/forecasting" class="text-amber-500 text-xs font-black uppercase tracking-widest flex items-center gap-2 group-hover:gap-3 transition-all">
                        Pelajari Lebih Lanjut
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
