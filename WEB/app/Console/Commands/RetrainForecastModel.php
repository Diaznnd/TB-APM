<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Transaction;
use App\Services\ForecastingService;
use Illuminate\Support\Facades\Log;

class RetrainForecastModel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'forecast:retrain {--days=90 : Minimum hari data transaksi yang dikirim}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kirim data transaksi ke Flask untuk melatih ulang model forecasting, lalu ambil prediksi terbaru.';

    /**
     * Execute the console command.
     */
    public function handle(ForecastingService $forecastingService)
    {
        $this->info('Memulai proses retrain model forecasting...');

        if (!$forecastingService->isApiAlive()) {
            $this->error('Flask API tidak dapat diakses. Pastikan API berjalan di ' . config('services.ml_api.url', env('ML_API_URL')));
            return Command::FAILURE;
        }

        // Ambil data transaksi (default: 90 hari terakhir untuk memastikan cukup data)
        // Di kenyataan, Anda bisa mengubah batas ini sesuai kebutuhan atau mengirim semua data.
        $daysToFetch = (int) $this->option('days');
        
        $transactions = Transaction::orderBy('date_time', 'asc')
            ->when($daysToFetch > 0, function($query) use ($daysToFetch) {
                return $query->where('date_time', '>=', now()->subDays($daysToFetch));
            })
            ->get();

        if ($transactions->isEmpty()) {
            $this->warn('Data transaksi kosong di database. Tidak dapat melakukan retrain.');
            return Command::SUCCESS;
        }

        $this->info("Ditemukan {$transactions->count()} baris transaksi. Mengirim ke Flask...");

        try {
            // 1. Retrain Model
            $retrainResult = $forecastingService->retrainModel($transactions);
            
            if (empty($retrainResult)) {
                $this->error('Retrain gagal: Flask tidak mengembalikan hasil yang valid.');
                return Command::FAILURE;
            }

            if (isset($retrainResult['total_model']['trained']) && $retrainResult['total_model']['trained']) {
                $this->info("Retrain total_model SUKSES.");
            } else {
                $this->warn("Retrain total_model GAGAL: " . ($retrainResult['total_model']['alasan'] ?? 'Unknown'));
            }

            $this->info("Total produk berhasil dilatih: " . ($retrainResult['total_produk_dilatih'] ?? 0));

            // 2. Fetch prediksi baru setelah retrain
            $this->info('Mengambil prediksi 7 hari ke depan (Total)...');
            $forecastingService->fetchAndSaveWeeklyForecast(7, 'kasir');

            $this->info('Mengambil prediksi 7 hari ke depan (Per Produk)...');
            $forecastingService->fetchAndSaveWeeklyForecastByProduct(7, 'kasir');

            $this->info('Proses retrain dan update prediksi SELESAI!');
            Log::info('[RetrainForecastModel] Command completed successfully.');

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error("Terjadi kesalahan: " . $e->getMessage());
            Log::error('[RetrainForecastModel] Error: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
