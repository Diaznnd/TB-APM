<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel transaksi kasir bakery.
     * Kolom dataset (dipakai training) + kolom pelengkap kasir.
     *
     * Kolom dari dataset.csv:
     *   transaction_id  – ID struk belanja  (kolom 'Transaction')
     *   item            – nama produk       (kolom 'Item')
     *   date_time       – waktu transaksi   (kolom 'date_time', format DD-MM-YYYY HH:MM)
     *   period_day      – morning/afternoon/evening/night
     *   weekday_weekend – weekday/weekend
     *
     * Kolom pelengkap kasir (TIDAK dipakai training):
     *   quantity        – jumlah item yang dibeli
     *   harga_satuan    – harga per item
     *   subtotal        – quantity × harga_satuan
     *   kasir           – nama kasir yang melayani
     *   metode_bayar    – cash / debit / qris
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            // == Kolom yang selaras dengan dataset.csv ==
            $table->unsignedBigInteger('transaction_id')->comment('ID struk belanja (bisa 1 struk = banyak item)');
            $table->string('item', 100)->comment('Nama produk');
            $table->dateTime('date_time')->comment('Waktu transaksi');
            $table->string('period_day', 20)->comment('morning / afternoon / evening / night');
            $table->string('weekday_weekend', 10)->comment('weekday / weekend');

            // == Atribut pelengkap kasir (tidak dipakai training ML) ==
            $table->unsignedSmallInteger('quantity')->default(1)->comment('Jumlah item dibeli');
            $table->decimal('harga_satuan', 10, 2)->default(0)->comment('Harga per item (Rp)');
            $table->decimal('subtotal', 10, 2)->default(0)->comment('quantity × harga_satuan');
            $table->string('kasir', 50)->nullable()->comment('Nama kasir');
            $table->string('metode_bayar', 20)->nullable()->comment('cash / debit / qris');

            $table->timestamps();

            // Index untuk query forecasting (ambil data per tanggal)
            $table->index('date_time');
            $table->index('item');
            $table->index('transaction_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
