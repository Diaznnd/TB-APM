@extends('layouts.app')

@section('title', 'Riwayat Transaksi')

@section('content')

<div class="space-y-6">

    <!-- HEADER -->
    <div class="flex justify-between items-center">

        <div>
            <h1 class="text-3xl font-bold text-slate-800 dark:text-white">
                Riwayat Transaksi
            </h1>

            <p class="text-slate-500 dark:text-slate-400 mt-1">
                Semua data transaksi penjualan
            </p>
        </div>

        <a href="{{ route('transactions.index') }}"
           class="bg-yellow-600 hover:bg-yellow-700 text-white px-5 py-2.5 rounded-xl transition">
            Kembali ke POS
        </a>

    </div>

    <!-- FILTER -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm p-5 border border-slate-100 dark:border-slate-700">

        <div class="grid grid-cols-3 gap-4">

            <!-- SEARCH -->
            <div>
                <label class="text-sm text-slate-500 dark:text-slate-400">
                    Cari Produk
                </label>

                <input
                    type="text"
                    id="searchInput"
                    placeholder="Cari nama produk..."
                    class="w-full mt-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 rounded-xl px-4 py-3"
                >
            </div>

            <!-- FILTER DATE -->
            <div>
                <label class="text-sm text-slate-500 dark:text-slate-400">
                    Filter Tanggal
                </label>

                <input
                    type="date"
                    id="dateFilter"
                    class="w-full mt-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 rounded-xl px-4 py-3"
                >
            </div>

            <!-- TOTAL -->
            <div class="bg-yellow-50 dark:bg-slate-900 rounded-xl p-4 flex flex-col justify-center">

                <span class="text-sm text-slate-500 dark:text-slate-400">
                    Total Pendapatan
                </span>

                <h2 id="grandTotal"
                    class="text-2xl font-bold text-yellow-600 mt-1">
                    Rp 0
                </h2>

            </div>

        </div>

    </div>

    <!-- TABLE -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm p-6 border border-slate-100 dark:border-slate-700">

        <div class="overflow-x-auto">

            <table class="w-full">

                <thead>

                    <tr class="border-b dark:border-slate-700 text-slate-500 dark:text-slate-400">

                        <th class="text-left py-4">ID</th>
                        <th class="text-left py-4">Produk</th>
                        <th class="text-left py-4">Qty</th>
                        <th class="text-left py-4">Harga</th>
                        <th class="text-left py-4">Subtotal</th>
                        <th class="text-left py-4">Pembayaran</th>
                        <th class="text-left py-4">Tanggal</th>

                    </tr>

                </thead>

                <tbody id="transactionTable">

                    @foreach($transactions as $trx)

                    <tr
                        class="border-b dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-900 transition transaction-row"
                        data-item="{{ strtolower($trx->item) }}"
                        data-date="{{ \Carbon\Carbon::parse($trx->date_time)->format('Y-m-d') }}"
                        data-subtotal="{{ $trx->subtotal }}"
                    >

                        <td class="py-4 font-semibold text-slate-700 dark:text-slate-200">
                            #{{ $trx->transaction_id }}
                        </td>

                        <td class="py-4 text-slate-700 dark:text-slate-200">
                            {{ $trx->item }}
                        </td>

                        <td class="py-4">
                            {{ $trx->quantity }}
                        </td>

                        <td class="py-4">
                            Rp {{ number_format($trx->harga_satuan,0,',','.') }}
                        </td>

                        <td class="py-4 font-semibold text-yellow-600">
                            Rp {{ number_format($trx->subtotal,0,',','.') }}
                        </td>

                        <td class="py-4">

                            @if($trx->metode_bayar == 'cash')

                                <span class="bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full text-xs font-semibold">
                                    Cash
                                </span>

                            @elseif($trx->metode_bayar == 'qris')

                                <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-semibold">
                                    QRIS
                                </span>

                            @else

                                <span class="bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-xs font-semibold">
                                    Debit
                                </span>

                            @endif

                        </td>

                        <td class="py-4 text-slate-500 dark:text-slate-400">
                            {{ \Carbon\Carbon::parse($trx->date_time)->format('d M Y H:i') }}
                        </td>

                    </tr>

                    @endforeach

                </tbody>

            </table>

            <!-- EMPTY -->
            <div id="emptyMessage"
                 class="hidden text-center py-10 text-slate-400">
                Data transaksi tidak ditemukan
            </div>

        </div>

    </div>

</div>

<script>

function updateGrandTotal()
{
    let total = 0;

    document.querySelectorAll('.transaction-row')
    .forEach(row => {

        if(row.style.display !== 'none'){
            total += parseInt(row.dataset.subtotal);
        }

    });

    document.getElementById('grandTotal').innerText =
        'Rp ' + total.toLocaleString();
}

function filterData()
{
    const keyword =
        document.getElementById('searchInput')
        .value
        .toLowerCase();

    const selectedDate =
        document.getElementById('dateFilter')
        .value;

    let visible = 0;

    document.querySelectorAll('.transaction-row')
    .forEach(row => {

        const item = row.dataset.item;
        const date = row.dataset.date;

        let matchSearch =
            item.includes(keyword);

        let matchDate =
            selectedDate === ''
            || date === selectedDate;

        if(matchSearch && matchDate){

            row.style.display = '';

            visible++;

        } else {

            row.style.display = 'none';

        }

    });

    document.getElementById('emptyMessage').style.display =
        visible === 0 ? 'block' : 'none';

    updateGrandTotal();
}

document.getElementById('searchInput')
.addEventListener('keyup', filterData);

document.getElementById('dateFilter')
.addEventListener('change', filterData);

updateGrandTotal();

</script>

@endsection