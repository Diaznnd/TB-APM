<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        // Kolom selaras dataset.csv
        'transaction_id',
        'item',
        'date_time',
        'period_day',
        'weekday_weekend',
        // Kolom pelengkap kasir
        'quantity',
        'harga_satuan',
        'subtotal',
        'kasir',
        'metode_bayar',
    ];

    protected $casts = [
        'date_time'    => 'datetime',
        'harga_satuan' => 'decimal:2',
        'subtotal'     => 'decimal:2',
        'quantity'     => 'integer',
    ];

    /**
     * Format tanggal ke format yang digunakan dataset.csv: "DD-MM-YYYY HH:MM"
     */
    public function getDateTimeDatasetFormat(): string
    {
        return $this->date_time->format('d-m-Y H:i');
    }

    /**
     * Scope: ambil transaksi dalam rentang tanggal tertentu.
     */
    public function scopeInDateRange($query, string $from, string $to)
    {
        return $query->whereBetween('date_time', [$from . ' 00:00:00', $to . ' 23:59:59']);
    }

    /**
     * Scope: ambil transaksi N minggu terakhir.
     */
    public function scopeLastWeeks($query, int $weeks = 1)
    {
        return $query->where('date_time', '>=', now()->subWeeks($weeks)->startOfDay());
    }

    /**
     * Konversi record ini ke format yang bisa dikirim ke Flask /retrain.
     */
    public function toFlaskFormat(): array
    {
        return [
            'transaction_id'  => $this->transaction_id,
            'item'            => $this->item,
            'date_time'       => $this->getDateTimeDatasetFormat(),
            'period_day'      => $this->period_day,
            'weekday_weekend' => $this->weekday_weekend,
        ];
    }
}
