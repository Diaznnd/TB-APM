<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Forecast;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today    = Carbon::today();
        $yesterday = Carbon::yesterday();

        // ── Total transaksi hari ini (jumlah transaction_id unik) ──
        $totalTransaksi = Transaction::whereDate('date_time', $today)
            ->distinct('transaction_id')->count('transaction_id');

        $txKemarin = Transaction::whereDate('date_time', $yesterday)
            ->distinct('transaction_id')->count('transaction_id');

        $changeTransaksi = $txKemarin > 0
            ? round((($totalTransaksi - $txKemarin) / $txKemarin) * 100)
            : 0;

        // ── Pendapatan hari ini ──
        $totalPendapatan = Transaction::whereDate('date_time', $today)->sum('subtotal');
        $pendapatanKemarin = Transaction::whereDate('date_time', $yesterday)->sum('subtotal');
        $changePendapatan = $pendapatanKemarin > 0
            ? round((($totalPendapatan - $pendapatanKemarin) / $pendapatanKemarin) * 100)
            : 0;

        // ── Stok menipis (mengambil data dari model Product dengan stok <= 10) ──
        $stokMenipis = \App\Models\Product::where('stock', '<=', 10)->count();

        // ── Prediksi besok (dari tabel forecasts) ──
        $tomorrow = Carbon::tomorrow()->toDateString();
        $prediksiRows = Forecast::where('tanggal_prediksi', $tomorrow)
            ->orderByDesc('prediksi_transaksi')
            ->get(['mode', 'prediksi_transaksi']);

        $isFallbackDate = false;
        $prediksiTanggal = $tomorrow;

        if ($prediksiRows->isEmpty()) {
            $latestPredictionDate = Forecast::latest('tanggal_prediksi')->value('tanggal_prediksi');
            if ($latestPredictionDate) {
                $prediksiTanggal = $latestPredictionDate instanceof Carbon
                    ? $latestPredictionDate->toDateString()
                    : Carbon::parse($latestPredictionDate)->toDateString();

                $prediksiRows = Forecast::where('tanggal_prediksi', $prediksiTanggal)
                    ->orderByDesc('prediksi_transaksi')
                    ->get(['mode', 'prediksi_transaksi']);
                $isFallbackDate = true;
            }
        }

        $prediksiTotal = $prediksiRows->sum('prediksi_transaksi') ?: 0;
        $forecastBesok = $prediksiRows->take(5)->toArray();

        // ── Akurasi model terbaru ──
        $latestForecast = Forecast::latest('generated_at')->first();
        $modelAkurasi   = '0.923'; // store di config atau tabel settings jika ada

        // ── Top produk hari ini ──
        $topRaw = Transaction::whereDate('date_time', $today)
            ->selectRaw('item, SUM(quantity) as qty')
            ->groupBy('item')
            ->orderByDesc('qty')
            ->take(5)
            ->get();

        $maxQty = $topRaw->max('qty') ?: 1;
        $topProduk = $topRaw->map(fn($r) => [
            'item' => $r->item,
            'qty'  => $r->qty,
            'pct'  => round(($r->qty / $maxQty) * 100),
        ])->toArray();

        // ── Grafik 7 hari terakhir ──
        $data7Hari = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $pendapatan = Transaction::whereDate('date_time', $date)->sum('subtotal');
            $txCount    = Transaction::whereDate('date_time', $date)
                ->distinct('transaction_id')->count('transaction_id');
            $data7Hari[] = [
                'label'      => $date->translatedFormat('D'),
                'pendapatan' => (int) $pendapatan,
                'transaksi'  => $txCount,
            ];
        }

        // ── Periode penjualan hari ini ──
        $periodeRaw = Transaction::whereDate('date_time', $today)
            ->selectRaw('period_day, SUM(quantity) as value')
            ->groupBy('period_day')
            ->get();

        $periodeColors = [
            'morning'   => '#C8963E',
            'afternoon' => '#3D1F0A',
            'evening'   => '#4a7c59',
            'night'     => '#b8a898',
        ];
        $periodeData = $periodeRaw->map(fn($r) => [
            'label' => ucfirst($r->period_day),
            'value' => (int) $r->value,
            'color' => $periodeColors[$r->period_day] ?? '#8a7a6a',
        ])->toArray();

        // ── Weekday data (30 hari terakhir) ──
        $days = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
        $weekdayRaw = Transaction::where('date_time', '>=', Carbon::now()->subDays(30))
            ->selectRaw('DAYOFWEEK(date_time) as dow, AVG(subtotal) as avg')
            ->groupBy('dow')
            ->orderBy('dow')
            ->get();

        $weekdayData = [];
        for ($d = 0; $d <= 6; $d++) {
            $row = $weekdayRaw->firstWhere('dow', $d + 1);
            $weekdayData[] = [
                'day' => $days[$d],
                'avg' => $row ? round($row->avg) : 0,
            ];
        }

        // Weekend boost
        $avgWeekday = Transaction::where('date_time', '>=', Carbon::now()->subDays(30))
            ->where('weekday_weekend', 'weekday')->avg('subtotal') ?: 0;
        $avgWeekend = Transaction::where('date_time', '>=', Carbon::now()->subDays(30))
            ->where('weekday_weekend', 'weekend')->avg('subtotal') ?: 0;
        $weekendBoost = $avgWeekday > 0
            ? round((($avgWeekend - $avgWeekday) / $avgWeekday) * 100)
            : 0;

        // ── Transaksi terbaru ──
        $transaksiTerbaru = Transaction::whereDate('date_time', $today)
            ->orderByDesc('date_time')
            ->take(10)
            ->get(['transaction_id','item','quantity','subtotal','kasir','metode_bayar','date_time'])
            ->toArray();

        return view('welcome', compact(
            'totalTransaksi', 'changeTransaksi',
            'totalPendapatan', 'changePendapatan',
            'stokMenipis',
            'prediksiTotal', 'forecastBesok', 'modelAkurasi',
            'topProduk',
            'data7Hari',
            'periodeData',
            'weekdayData', 'weekendBoost',
            'transaksiTerbaru',
            'isFallbackDate', 'prediksiTanggal'
        ));
    }
}