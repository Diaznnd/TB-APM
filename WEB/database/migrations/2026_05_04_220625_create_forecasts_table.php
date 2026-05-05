<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel cache hasil forecasting.
     * Mendukung dua mode prediksi:
     *   - 'total'       : total transaksi semua produk per hari
     *   - nama_produk   : transaksi produk tertentu per hari (mis. 'Bread')
     *
     * Sumber data model:
     *   - 'dataset' : model dilatih dari dataset.csv awal
     *   - 'kasir'   : model dilatih ulang dari data transaksi di DB
     */
    public function up(): void
    {
        Schema::create('forecasts', function (Blueprint $table) {
            $table->id();

            $table->date('tanggal_prediksi')->comment('Tanggal yang diprediksi');
            $table->string('mode', 100)->default('total')
                  ->comment('"total" = semua produk, atau nama produk spesifik');
            $table->unsignedInteger('prediksi_transaksi')
                  ->comment('Jumlah transaksi yang diprediksi');
            $table->json('fitur')->nullable()
                  ->comment('Fitur input model: lag_1, lag_2, day_of_week, month, ma_3');
            $table->string('sumber', 20)->default('dataset')
                  ->comment('"dataset" = dari CSV awal, "kasir" = dari data DB');
            $table->timestamp('generated_at')->useCurrent()
                  ->comment('Kapan prediksi ini dibuat');

            $table->timestamps();

            // Index untuk query tampilan forecasting
            $table->index('tanggal_prediksi');
            $table->index(['mode', 'tanggal_prediksi']);
            $table->index('generated_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('forecasts');
    }
};
