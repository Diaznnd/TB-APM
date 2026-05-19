@extends('layouts.app')

@section('title', 'Riwayat Transaksi')

@section('content')

<div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm p-6">

    <div class="flex justify-between items-center mb-6">

        <h1 class="text-3xl font-bold text-slate-800 dark:text-white">
            Riwayat Transaksi
        </h1>

        <a href="{{ route('transactions.index') }}"
           class="bg-yellow-600 text-white px-4 py-2 rounded-xl">
            Kembali
        </a>

    </div>

    <div class="overflow-x-auto">

        <table class="w-full">

            <thead>
                <tr class="border-b">
                    <th class="text-left py-3">ID Transaksi</th>
                    <th class="text-left py-3">Produk</th>
                    <th class="text-left py-3">Qty</th>
                    <th class="text-left py-3">Subtotal</th>
                    <th class="text-left py-3">Tanggal</th>
                </tr>
            </thead>

            <tbody>

                @foreach($transactions as $trx)

                <tr class="border-b">

                    <td class="py-3">
                        #{{ $trx->transaction_id }}
                    </td>

                    <td>
                        {{ $trx->item }}
                    </td>

                    <td>
                        {{ $trx->quantity }}
                    </td>

                    <td>
                        Rp {{ number_format($trx->subtotal,0,',','.') }}
                    </td>

                    <td>
                        {{ $trx->date_time }}
                    </td>

                </tr>

                @endforeach

            </tbody>

        </table>

    </div>

</div>

@endsection