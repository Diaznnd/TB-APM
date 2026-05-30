<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Penjualan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #8B5E34;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
            color: #8B5E34;
            text-transform: uppercase;
        }
        .header p {
            margin: 5px 0 0 0;
            font-size: 12px;
            color: #666;
        }
        .info-box {
            width: 100%;
            margin-bottom: 20px;
        }
        .info-box td {
            vertical-align: top;
        }
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .summary-table th, .summary-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }
        .summary-table th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #555;
            text-transform: uppercase;
            font-size: 10px;
        }
        .summary-table td {
            font-size: 16px;
            font-weight: bold;
            color: #8B5E34;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #8B5E34;
            margin-bottom: 10px;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 11px;
        }
        .data-table th, .data-table td {
            border: 1px solid #ddd;
            padding: 6px;
        }
        .data-table th {
            background-color: #8B5E34;
            color: #fff;
            text-align: left;
        }
        .data-table td {
            text-align: left;
        }
        .data-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .text-right {
            text-align: right !important;
        }
        .text-center {
            text-align: center !important;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 12px;
        }
        .signature-area {
            margin-top: 50px;
            display: inline-block;
            text-align: center;
        }
        .signature-line {
            border-top: 1px solid #333;
            width: 150px;
            margin-top: 50px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Laporan Penjualan Golden Tulip Bakery</h1>
        <p>Periode: {{ $periode }} | Tanggal Cetak: {{ date('d F Y H:i') }}</p>
    </div>

    <!-- Executive Summary -->
    <div class="section-title">Ringkasan Eksekutif</div>
    <table class="summary-table">
        <tr>
            <th>Total Pendapatan</th>
            <th>Total Transaksi</th>
            <th>Total Item Terjual</th>
            <th>Rata-rata Transaksi</th>
        </tr>
        <tr>
            <td>Rp {{ number_format($summary['total_revenue'], 0, ',', '.') }}</td>
            <td>{{ number_format($summary['total_transactions']) }}</td>
            <td>{{ number_format($summary['total_items']) }}</td>
            <td>Rp {{ number_format($summary['avg_order_value'], 0, ',', '.') }}</td>
        </tr>
    </table>

    <!-- Mini Analysis -->
    <table style="width: 100%; margin-bottom: 20px;">
        <tr>
            <td style="width: 50%; vertical-align: top; padding-right: 10px;">
                <div class="section-title">5 Produk Terlaris</div>
                <table class="data-table">
                    <tr>
                        <th>Produk</th>
                        <th class="text-center">Qty</th>
                        <th class="text-right">Pendapatan</th>
                    </tr>
                    @foreach($topItems as $item)
                    <tr>
                        <td>{{ $item->item }}</td>
                        <td class="text-center">{{ $item->total_qty }}</td>
                        <td class="text-right">Rp {{ number_format($item->total_revenue, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </table>
            </td>
            <td style="width: 50%; vertical-align: top; padding-left: 10px;">
                <div class="section-title">Pendapatan by Metode Bayar</div>
                <table class="data-table">
                    <tr>
                        <th>Metode</th>
                        <th class="text-center">Trx</th>
                        <th class="text-right">Pendapatan</th>
                    </tr>
                    @foreach($paymentMethods as $method)
                    <tr>
                        <td><span style="text-transform: capitalize;">{{ $method->metode_bayar ?: 'Lainnya' }}</span></td>
                        <td class="text-center">{{ $method->total_trx }}</td>
                        <td class="text-right">Rp {{ number_format($method->total_revenue, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </table>
            </td>
        </tr>
    </table>

    <div style="page-break-after: auto;"></div>

    <!-- Details Table -->
    <div class="section-title">Rincian Transaksi</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 15%">Waktu</th>
                <th style="width: 10%">Struk</th>
                <th style="width: 25%">Produk</th>
                <th style="width: 5%" class="text-center">Qty</th>
                <th style="width: 15%" class="text-right">Harga</th>
                <th style="width: 15%" class="text-right">Subtotal</th>
                <th style="width: 15%">Metode</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $trx)
            <tr>
                <td>{{ \Carbon\Carbon::parse($trx->date_time)->format('d/m/Y H:i') }}</td>
                <td>#{{ $trx->transaction_id }}</td>
                <td>{{ $trx->item }}</td>
                <td class="text-center">{{ $trx->quantity }}</td>
                <td class="text-right">{{ number_format($trx->harga_satuan, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($trx->subtotal, 0, ',', '.') }}</td>
                <td><span style="text-transform: capitalize;">{{ $trx->metode_bayar }}</span></td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Footer / Signature -->
    <div class="footer">
        <div class="signature-area">
            <p>Mengetahui,</p>
            <div class="signature-line"></div>
            <p style="margin-top: 5px;">Manajer Golden Tulip Bakery</p>
        </div>
    </div>

</body>
</html>
