<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ForecastingService;
use App\Models\Forecast;
use Illuminate\Support\Facades\Log;

class ForecastingController extends Controller
{
    protected $forecastingService;

    public function __construct(ForecastingService $forecastingService)
    {
        $this->forecastingService = $forecastingService;
    }

    /**
     * Tampilkan halaman utama forecasting.
     */
    public function index()
    {
        $forecastData = $this->forecastingService->getForecastFromDb();
        $metrics      = $this->forecastingService->getModelMetrics();
        $isApiAlive   = $this->forecastingService->isApiAlive();

        return view('forecasting.index', compact('forecastData', 'metrics', 'isApiAlive'));
    }

    /**
     * Trigger refresh prediksi secara manual.
     */
    public function refresh(Request $request)
    {
        try {
            if (!$this->forecastingService->isApiAlive()) {
                return back()->with('error', 'Flask API tidak aktif. Gagal memperbarui prediksi.');
            }

            // Ambil seluruh data transaksi kasir untuk melatih ulang model secara dinamis (expanding window)
            $transactions = \App\Models\Transaction::orderBy('date_time', 'asc')->get();

            if ($transactions->isNotEmpty()) {
                // Melatih ulang model ML di Flask dengan data kasir asli
                $this->forecastingService->retrainModel($transactions);
                
                // Ambil prediksi total & per produk bersumber dari data kasir terbaru (sinkronisasi tanggal hari ini)
                $this->forecastingService->fetchAndSaveWeeklyForecast(7, 'kasir');
                $this->forecastingService->fetchAndSaveWeeklyForecastByProduct(7, 'kasir');

                return back()->with('success', 'Model ML berhasil dilatih ulang dan prediksi 7 hari ke depan diperbarui berdasarkan data transaksi kasir riil.');
            } else {
                // Fallback jika database transaksi kasir masih kosong
                $this->forecastingService->fetchAndSaveWeeklyForecast(7, 'dataset');
                $this->forecastingService->fetchAndSaveWeeklyForecastByProduct(7, 'dataset');

                return back()->with('success', 'Prediksi berhasil diperbarui dari Flask API menggunakan dataset default.');
            }
        } catch (\Exception $e) {
            Log::error('[ForecastingController] refresh error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memperbarui prediksi: ' . $e->getMessage());
        }
    }

    /**
     * Ambil metrik model dalam format JSON (untuk AJAX/Polling jika perlu).
     */
    public function metrics()
    {
        return response()->json($this->forecastingService->getModelMetrics());
    }
}
