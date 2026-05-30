<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Product;

class TransactionController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('name')->get();

        return view('transaksi.index', compact('products'));
    }

    public function store(Request $request)
    {
        $cart = $request->cart;

        if (!$cart || count($cart) == 0) {
            return response()->json([
                'success' => false,
                'message' => 'Keranjang kosong'
            ]);
        }

        $transactionId = rand(10000,99999);

        foreach ($cart as $item) {

            $product = Product::find($item['id']);

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Produk tidak ditemukan'
                ]);
            }

            if ($product->stock < $item['qty']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stok ' . $product->name . ' tidak cukup'
                ]);
            }

            // kurangi stok
            $product->stock -= $item['qty'];
            $product->save();

            $date = now();

            $hour = $date->format('H');

            if ($hour >= 5 && $hour < 12) {
                $period = 'morning';
            } elseif ($hour < 17) {
                $period = 'afternoon';
            } elseif ($hour < 21) {
                $period = 'evening';
            } else {
                $period = 'night';
            }

            $weekdayWeekend = $date->isWeekend()
                ? 'weekend'
                : 'weekday';

            Transaction::create([
                'transaction_id' => $transactionId,
                'item' => $product->name,
                'date_time' => now(),
                'period_day' => $period,
                'weekday_weekend' => $weekdayWeekend,
                'quantity' => $item['qty'],
                'harga_satuan' => $product->price,
                'subtotal' => $item['qty'] * $product->price,
                'kasir' => $request->cashier ?? 'Admin',
                'metode_bayar' => $request->metode_bayar ?? 'cash',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Transaksi berhasil!'
        ]);
    }

    public function history(Request $request)
    {
        $query = Transaction::latest();

        // Penyaringan berdasarkan kata kunci pencarian produk (server-side)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('item', 'like', '%' . $search . '%');
        }

        // Penyaringan berdasarkan tanggal transaksi (server-side)
        if ($request->filled('date')) {
            $query->whereDate('date_time', $request->date);
        }

        // Hitung total pendapatan dari hasil yang terfilter sebelum dipaginasi
        $grandTotal = $query->sum('subtotal');

        // Paginasi 20 data per halaman dengan mempertahankan query string saringan
        $transactions = $query->paginate(20)->withQueryString();

        return view('transaksi.history', compact('transactions', 'grandTotal'));
    }
}