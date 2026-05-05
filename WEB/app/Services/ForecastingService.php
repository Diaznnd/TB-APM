<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Forecast;
use Carbon\Carbon;

class ForecastingService
{
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('services.ml_api.url', env('ML_API_URL', 'http://localhost:5000')), '/');
    }

    // ----------------------------------------------------------------
    // Health Check
    // ----------------------------------------------------------------

    /**
     * Cek apakah Flask API sedang berjalan.
     */
    public function isApiAlive(): bool
    {
        try {
            $response = Http::timeout(5)->get("{$this->baseUrl}/health");
            return $response->successful();
        } catch (\Exception $e) {
            return false;
        }
    }

    // ----------------------------------------------------------------
    // Metrics
    // ----------------------------------------------------------------

    /**
     * Ambil metrik evaluasi model dari Flask (MAE, RMSE, R²).
     *
     * @return array{mae: array, rmse: array, r2: array}|array{error: string}
     */
    public function getModelMetrics(): array
    {
        try {
            $response = Http::timeout(10)->get("{$this->baseUrl}/metrics");

            if ($response->failed()) {
                return ['error' => 'Flask API mengembalikan error: ' . $response->status()];
            }

            return $response->json();
        } catch (\Exception $e) {
            Log::error('[ForecastingService] getModelMetrics error: ' . $e->getMessage());
            return ['error' => 'Tidak bisa terhubung ke Flask API: ' . $e->getMessage()];
        }
    }

    // ----------------------------------------------------------------
    // Weekly Forecast — Total
    // ----------------------------------------------------------------

    /**
     * Ambil prediksi 7 hari ke depan (total semua produk) dari Flask,
     * simpan ke tabel forecasts, dan kembalikan hasilnya.
     *
     * @param  int    $days   Jumlah hari yang diprediksi (default 7)
     * @param  string $sumber 'dataset' atau 'kasir'
     * @return array
     */
    public function fetchAndSaveWeeklyForecast(int $days = 7, string $sumber = 'dataset'): array
    {
        try {
            $response = Http::timeout(30)->get("{$this->baseUrl}/predict/weekly", [
                'days' => $days,
            ]);

            if ($response->failed()) {
                throw new \RuntimeException('Flask API error: ' . $response->body());
            }

            $data = $response->json();

            // Hapus prediksi lama untuk mode 'total' di tanggal yang sama
            Forecast::where('mode', 'total')
                    ->where('tanggal_prediksi', '>=', today())
                    ->delete();

            // Simpan prediksi baru
            $generatedAt = now();
            foreach ($data['prediksi'] ?? [] as $item) {
                Forecast::create([
                    'tanggal_prediksi'   => $item['tanggal'],
                    'mode'               => 'total',
                    'prediksi_transaksi' => $item['prediksi'],
                    'fitur'              => $item['fitur'] ?? null,
                    'sumber'             => $sumber,
                    'generated_at'       => $generatedAt,
                ]);
            }

            Log::info('[ForecastingService] Prediksi total berhasil disimpan.', [
                'days'   => $days,
                'sumber' => $sumber,
            ]);

            return $data;

        } catch (\Exception $e) {
            Log::error('[ForecastingService] fetchAndSaveWeeklyForecast error: ' . $e->getMessage());
            throw $e;
        }
    }

    // ----------------------------------------------------------------
    // Weekly Forecast — Per Produk
    // ----------------------------------------------------------------

    /**
     * Ambil prediksi 7 hari ke depan per-produk dari Flask,
     * simpan ke tabel forecasts, dan kembalikan hasilnya.
     *
     * @param  int    $days   Jumlah hari yang diprediksi (default 7)
     * @param  string $sumber 'dataset' atau 'kasir'
     * @return array
     */
    public function fetchAndSaveWeeklyForecastByProduct(int $days = 7, string $sumber = 'dataset'): array
    {
        try {
            $response = Http::timeout(60)->get("{$this->baseUrl}/predict/weekly/produk", [
                'days' => $days,
            ]);

            if ($response->failed()) {
                throw new \RuntimeException('Flask API error: ' . $response->body());
            }

            $data = $response->json();

            // Hapus prediksi lama per-produk mulai hari ini
            Forecast::where('mode', '!=', 'total')
                    ->where('tanggal_prediksi', '>=', today())
                    ->delete();

            $generatedAt = now();

            foreach ($data['produk'] ?? [] as $productName => $predictions) {
                foreach ($predictions as $item) {
                    Forecast::create([
                        'tanggal_prediksi'   => $item['tanggal'],
                        'mode'               => $productName,
                        'prediksi_transaksi' => $item['prediksi'],
                        'fitur'              => $item['fitur'] ?? null,
                        'sumber'             => $sumber,
                        'generated_at'       => $generatedAt,
                    ]);
                }
            }

            Log::info('[ForecastingService] Prediksi per-produk berhasil disimpan.', [
                'total_produk' => $data['total_produk'] ?? 0,
                'sumber'       => $sumber,
            ]);

            return $data;

        } catch (\Exception $e) {
            Log::error('[ForecastingService] fetchAndSaveWeeklyForecastByProduct error: ' . $e->getMessage());
            throw $e;
        }
    }

    // ----------------------------------------------------------------
    // Retrain Model
    // ----------------------------------------------------------------

    /**
     * Kirim data transaksi dari DB ke Flask untuk melatih ulang model.
     *
     * @param  \Illuminate\Support\Collection $transactions  Collection of Transaction model
     * @return array  Hasil retrain dari Flask
     */
    public function retrainModel($transactions): array
    {
        if ($transactions->isEmpty()) {
            throw new \InvalidArgumentException('Tidak ada data transaksi untuk dikirim ke Flask.');
        }

        // Konversi ke format yang dimengerti Flask
        $payload = $transactions->map(fn ($t) => $t->toFlaskFormat())->values()->toArray();

        Log::info('[ForecastingService] Memulai retrain model.', [
            'total_rows' => count($payload),
        ]);

        try {
            $response = Http::timeout(300)  // Retrain bisa lama, timeout 5 menit
                ->post("{$this->baseUrl}/retrain", [
                    'transactions' => $payload,
                ]);

            if ($response->failed()) {
                throw new \RuntimeException(
                    'Flask retrain gagal (' . $response->status() . '): ' . $response->body()
                );
            }

            $result = $response->json();

            if (is_null($result)) {
                Log::warning('[ForecastingService] Flask retrain returned null or invalid JSON.', [
                    'status' => $response->status(),
                    'body'   => substr($response->body(), 0, 500),
                ]);
                return [];
            }

            Log::info('[ForecastingService] Retrain selesai.', [
                'total_model_trained'   => $result['total_model']['trained'] ?? false,
                'total_produk_dilatih'  => $result['total_produk_dilatih'] ?? 0,
            ]);

            return $result;

        } catch (\Exception $e) {
            Log::error('[ForecastingService] retrainModel error: ' . $e->getMessage());
            throw $e;
        }
    }

    // ----------------------------------------------------------------
    // Helper: Ambil data dari DB
    // ----------------------------------------------------------------

    /**
     * Ambil data forecasting dari DB untuk ditampilkan di halaman.
     *
     * @return array{total: array, per_produk: array, generated_at: string|null, sumber: string|null}
     */
    public function getForecastFromDb(): array
    {
        // Prediksi total
        $totalForecasts = Forecast::total()
            ->upcoming(7)
            ->latestGenerated()
            ->get();

        // Prediksi per-produk (ambil semua mode selain 'total')
        $productForecasts = Forecast::where('mode', '!=', 'total')
            ->upcoming(7)
            ->latestGenerated()
            ->get()
            ->groupBy('mode');

        $generatedAt = $totalForecasts->first()?->generated_at;
        $sumber      = $totalForecasts->first()?->sumber;

        return [
            'total'        => $totalForecasts->values()->toArray(),
            'per_produk'   => $productForecasts->toArray(),
            'generated_at' => $generatedAt?->format('d M Y H:i'),
            'sumber'       => $sumber,
        ];
    }
}
