@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Forecasting Penjualan</h1>
            <p class="text-slate-500 mt-1">Prediksi jumlah transaksi menggunakan model Machine Learning Random Forest.</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="px-3 py-1 bg-{{ $isApiAlive ? 'emerald' : 'rose' }}-100 text-{{ $isApiAlive ? 'emerald' : 'rose' }}-700 rounded-full text-xs font-semibold flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-{{ $isApiAlive ? 'emerald' : 'rose' }}-500"></span>
                API Flask: {{ $isApiAlive ? 'Aktif' : 'Tidak Aktif' }}
            </div>
            <form action="{{ route('forecasting.refresh') }}" method="POST">
                @csrf
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-white border border-slate-300 rounded-lg shadow-sm text-sm font-medium text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 transition-all">
                    <i class="fas fa-sync-alt mr-2 {{ session('success') ? '' : 'fa-spin' }}"></i>
                    Refresh Prediksi
                </button>
            </form>
        </div>
    </div>

    <!-- Metrics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach(['mae' => 'Mean Absolute Error', 'rmse' => 'Root Mean Squared Error', 'r2' => 'R-Squared (Akurasi)'] as $key => $label)
            <div class="glass-card p-6 rounded-2xl shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <span class="text-slate-500 text-sm font-medium">{{ $label }}</span>
                    <div class="p-2 bg-amber-50 rounded-lg">
                        <i class="fas fa-{{ $key == 'r2' ? 'chart-line' : 'bullseye' }} text-amber-600"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <h3 class="text-3xl font-bold text-slate-900">
                        {{ isset($metrics[$key]['nilai']) ? number_format($metrics[$key]['nilai'], 3) : '-' }}
                    </h3>
                    <span class="text-{{ (isset($metrics[$key]['interpretasi']) && $metrics[$key]['interpretasi'] == 'Sangat baik') ? 'emerald' : 'amber' }}-600 text-xs font-semibold uppercase tracking-wider">
                        {{ $metrics[$key]['interpretasi'] ?? 'N/A' }}
                    </h3>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Main Chart Section -->
    <div class="glass-card p-8 rounded-2xl shadow-sm">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h3 class="text-xl font-bold text-slate-900">Prediksi Transaksi 7 Hari Kedepan</h3>
                <p class="text-slate-500 text-sm mt-1">Berdasarkan data {{ $forecastData['sumber'] == 'kasir' ? 'kasir harian' : 'dataset awal' }}. Terakhir diperbarui: {{ $forecastData['generated_at'] ?? 'Belum ada data' }}</p>
            </div>
            <div class="flex items-center bg-slate-100 p-1 rounded-lg">
                <button class="px-3 py-1.5 text-xs font-semibold rounded-md bg-white shadow-sm text-slate-900">Total Transaksi</button>
                <button class="px-3 py-1.5 text-xs font-semibold text-slate-500 hover:text-slate-700">Per Produk</button>
            </div>
        </div>
        <div class="h-[400px]">
            <canvas id="forecastChart"></canvas>
        </div>
    </div>

    <!-- Product Breakdown Table -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 glass-card rounded-2xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                <h3 class="font-bold text-slate-900">Detail Prediksi Mingguan</h3>
                <span class="text-xs text-slate-500 uppercase tracking-widest font-bold">Total Transaksi</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-slate-400 text-[10px] font-bold uppercase tracking-widest border-b border-slate-100">
                            <th class="px-6 py-4">Hari / Tanggal</th>
                            <th class="px-6 py-4">Prediksi Jumlah</th>
                            <th class="px-6 py-4">Moving Avg (3d)</th>
                            <th class="px-6 py-4">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($forecastData['total'] as $f)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 font-medium text-slate-900">{{ \Carbon\Carbon::parse($f['tanggal_prediksi'])->isoFormat('dddd, D MMM Y') }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-bold bg-amber-100 text-amber-800">
                                        {{ $f['prediksi_transaksi'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-slate-500 text-sm">{{ $f['fitur']['ma_3'] ?? '-' }}</td>
                                <td class="px-6 py-4">
                                    <span class="w-2 h-2 rounded-full inline-block bg-emerald-500 mr-2"></span>
                                    <span class="text-xs text-emerald-600 font-bold uppercase">Optimal</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-slate-400">
                                    <i class="fas fa-ghost text-4xl mb-4 block"></i>
                                    Belum ada data prediksi. Klik "Refresh Prediksi" untuk memulai.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="space-y-6">
            <div class="glass-card p-6 rounded-2xl shadow-sm bg-gradient-to-br from-amber-500 to-amber-600 text-white">
                <h3 class="font-bold text-lg mb-2">Automated Scheduler</h3>
                <p class="text-amber-100 text-sm leading-relaxed">Sistem akan melakukan pelatihan ulang model setiap hari pada pukul <span class="font-bold text-white">02:00 Pagi</span> menggunakan data kasir terbaru.</p>
                <div class="mt-4 pt-4 border-t border-white/20 flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div>
                        <p class="text-[10px] text-amber-100 uppercase font-bold tracking-widest">Next Retrain</p>
                        <p class="font-bold">Besok, 02:00 AM</p>
                    </div>
                </div>
            </div>

            <div class="glass-card p-6 rounded-2xl shadow-sm">
                <h3 class="font-bold text-slate-900 mb-4">Informasi Model</h3>
                <ul class="space-y-4">
                    <li class="flex items-start gap-3">
                        <div class="text-amber-600 mt-1"><i class="fas fa-check-circle"></i></div>
                        <div>
                            <p class="text-sm font-bold text-slate-900">Random Forest Regressor</p>
                            <p class="text-xs text-slate-500">Model ensemble yang handal untuk forecasting data musiman.</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-3">
                        <div class="text-amber-600 mt-1"><i class="fas fa-check-circle"></i></div>
                        <div>
                            <p class="text-sm font-bold text-slate-900">Fitur Training</p>
                            <p class="text-xs text-slate-500">Lag-1, Lag-2, MA-3, Hari, dan Bulan.</p>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const ctx = document.getElementById('forecastChart').getContext('2d');
    const forecastData = @json($forecastData['total']);
    
    const labels = forecastData.map(f => {
        const d = new Date(f.tanggal_prediksi);
        return d.toLocaleDateString('id-ID', { weekday: 'short', day: 'numeric' });
    });
    const values = forecastData.map(f => f.prediksi_transaksi);

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Prediksi Jumlah Transaksi',
                data: values,
                borderColor: '#d97706',
                backgroundColor: 'rgba(217, 119, 6, 0.1)',
                borderWidth: 4,
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#ffffff',
                pointBorderColor: '#d97706',
                pointBorderWidth: 2,
                pointRadius: 6,
                pointHoverRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    backgroundColor: '#1e293b',
                    titleColor: '#ffffff',
                    padding: 12,
                    cornerRadius: 8,
                    displayColors: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0,0,0,0.05)', drawBorder: false },
                    ticks: { color: '#64748b', font: { weight: 'bold' } }
                },
                x: {
                    grid: { display: false },
                    ticks: { color: '#64748b', font: { weight: 'bold' } }
                }
            }
        }
    });
</script>
@endpush
@endsection
