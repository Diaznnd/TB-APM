<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class InitialTransactionSeeder extends Seeder
{
    /**
     * Import data dari ML/data/dataset.csv ke tabel transactions.
     */
    public function run(): void
    {
        $csvPath = base_path('../ML/data/dataset.csv');
        
        if (!file_exists($csvPath)) {
            $this->command->error("File dataset tidak ditemukan di: {$csvPath}");
            return;
        }

        $this->command->info("Mengimpor data dari {$csvPath}...");

        $file = fopen($csvPath, 'r');
        $header = fgetcsv($file); // Skip header: Transaction,Item,date_time,period_day,weekday_weekend

        $batch = [];
        $count = 0;

        // Nonaktifkan log query untuk performa
        DB::connection()->disableQueryLog();

        while (($row = fgetcsv($file)) !== false) {
            // Mapping: 0:Transaction, 1:Item, 2:date_time, 3:period_day, 4:weekday_weekend
            
            // Format di CSV: 30-10-2016 09:58
            try {
                $dateTime = Carbon::createFromFormat('d-m-Y H:i', $row[2]);
                // Shift data harian ke tahun 2026 agar muncul di dashboard hari ini
                $dateTime->addYears(9)->addMonths(1); 
            } catch (\Exception $e) {
                continue; // Lewati jika format tanggal salah
            }

            $batch[] = [
                'transaction_id'  => $row[0],
                'item'            => $row[1],
                'date_time'       => $dateTime,
                'period_day'      => $row[3],
                'weekday_weekend' => $row[4],
                'quantity'        => 1,
                'harga_satuan'    => 0, // Data awal tidak ada harga
                'subtotal'        => 0,
                'kasir'           => 'System (Import)',
                'metode_bayar'    => 'Cash',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];

            $count++;

            // Insert per 500 baris agar tidak berat
            if (count($batch) >= 500) {
                Transaction::insert($batch);
                $batch = [];
                $this->command->info("Telah mengimpor {$count} baris...");
            }
        }

        if (count($batch) > 0) {
            Transaction::insert($batch);
        }

        fclose($file);
        $this->command->info("Selesai! Total {$count} transaksi diimpor.");
    }
}
