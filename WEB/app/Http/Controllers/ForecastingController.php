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

            // Ambil prediksi total
            $this->forecastingService->fetchAndSaveWeeklyForecast(7, 'dataset');
            
            // Ambil prediksi per produk
            $this->forecastingService->fetchAndSaveWeeklyForecastByProduct(7, 'dataset');

            return back()->with('success', 'Prediksi berhasil diperbarui dari Flask API.');
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
