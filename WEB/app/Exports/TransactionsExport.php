<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Http\Request;

class TransactionsExport implements FromQuery, WithHeadings, WithMapping
{
    protected $request;
    protected $rowNumber = 0;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function query()
    {
        $query = Transaction::query();

        if ($this->request->filled('start_date') && $this->request->filled('end_date')) {
            $query->whereBetween('date_time', [$this->request->start_date . ' 00:00:00', $this->request->end_date . ' 23:59:59']);
        }
        if ($this->request->filled('item')) {
            $query->where('item', $this->request->item);
        }
        if ($this->request->filled('kasir')) {
            $query->where('kasir', $this->request->kasir);
        }
        if ($this->request->filled('metode_bayar')) {
            $query->where('metode_bayar', $this->request->metode_bayar);
        }
        if ($this->request->filled('period_day')) {
            $query->where('period_day', $this->request->period_day);
        }

        return $query->orderBy('date_time', 'asc');
    }

    public function headings(): array
    {
        return [
            'No.',
            'Waktu Transaksi',
            'ID Struk',
            'Produk',
            'Kategori Waktu',
            'Hari (Wk/Wknd)',
            'Jumlah (Qty)',
            'Harga Satuan (Rp)',
            'Subtotal (Rp)',
            'Kasir',
            'Metode Pembayaran'
        ];
    }

    public function map($transaction): array
    {
        $this->rowNumber++;

        return [
            $this->rowNumber,
            $transaction->date_time,
            $transaction->transaction_id,
            $transaction->item,
            $transaction->period_day,
            $transaction->weekday_weekend,
            $transaction->quantity,
            $transaction->harga_satuan,
            $transaction->subtotal,
            $transaction->kasir,
            $transaction->metode_bayar,
        ];
    }
}
