<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Forecast extends Model
{
    use HasFactory;

    protected $fillable = [
        'tanggal_prediksi',
        'mode',
        'prediksi_transaksi',
        'fitur',
        'sumber',
        'generated_at',
    ];

    protected $casts = [
        'tanggal_prediksi'   => 'date:Y-m-d',
        'prediksi_transaksi' => 'integer',
        'fitur'              => 'array',    // JSON otomatis di-decode
        'generated_at'       => 'datetime',
    ];

    /**
     * Scope: ambil prediksi total (semua produk).
     */
    public function scopeTotal($query)
    {
        return $query->where('mode', 'total');
    }

    /**
     * Scope: ambil prediksi untuk produk tertentu.
     */
    public function scopeForProduct($query, string $product)
    {
        return $query->where('mode', $product);
    }

    /**
     * Scope: ambil prediksi 7 hari ke depan dari hari ini.
     */
    public function scopeUpcoming($query, int $days = 7)
    {
        return $query->where('tanggal_prediksi', '>=', today())
                     ->where('tanggal_prediksi', '<=', today()->addDays($days - 1))
                     ->orderBy('tanggal_prediksi');
    }

    /**
     * Scope: ambil prediksi terbaru berdasarkan generated_at.
     */
    public function scopeLatestGenerated($query)
    {
        return $query->orderBy('generated_at', 'desc');
    }
}
