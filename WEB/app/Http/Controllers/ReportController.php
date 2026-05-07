<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TransactionsExport;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::query();

        // Apply filters
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('date_time', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
        }
        if ($request->filled('item')) {
            $query->where('item', $request->item);
        }
        if ($request->filled('kasir')) {
            $query->where('kasir', $request->kasir);
        }
        if ($request->filled('metode_bayar')) {
            $query->where('metode_bayar', $request->metode_bayar);
        }
        if ($request->filled('period_day')) {
            $query->where('period_day', $request->period_day);
        }

        // Summary metrics
        $summary = [
            'total_revenue' => (clone $query)->sum('subtotal'),
            'total_transactions' => (clone $query)->distinct('transaction_id')->count('transaction_id'),
            'total_items' => (clone $query)->sum('quantity'),
            'avg_order_value' => 0,
        ];

        if ($summary['total_transactions'] > 0) {
            $summary['avg_order_value'] = $summary['total_revenue'] / $summary['total_transactions'];
        }

        // Dropdown options for filters
        $items = Transaction::select('item')->distinct()->pluck('item');
        $kasirs = Transaction::select('kasir')->whereNotNull('kasir')->distinct()->pluck('kasir');
        
        $transactions = $query->orderBy('date_time', 'desc')->paginate(15)->withQueryString();

        return view('laporan.index', compact('transactions', 'summary', 'items', 'kasirs'));
    }

    public function exportPdf(Request $request)
    {
        $query = Transaction::query();

        // Apply filters
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('date_time', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
            $periode = Carbon::parse($request->start_date)->translatedFormat('d F Y') . ' - ' . Carbon::parse($request->end_date)->translatedFormat('d F Y');
        } else {
             $periode = 'Semua Waktu';
        }
        
        if ($request->filled('item')) {
            $query->where('item', $request->item);
        }
        if ($request->filled('kasir')) {
            $query->where('kasir', $request->kasir);
        }
        if ($request->filled('metode_bayar')) {
            $query->where('metode_bayar', $request->metode_bayar);
        }
        if ($request->filled('period_day')) {
            $query->where('period_day', $request->period_day);
        }

        $transactions = $query->orderBy('date_time', 'asc')->get();

        $summary = [
            'total_revenue' => $transactions->sum('subtotal'),
            'total_transactions' => $transactions->unique('transaction_id')->count(),
            'total_items' => $transactions->sum('quantity'),
            'avg_order_value' => 0,
        ];
        
        if ($summary['total_transactions'] > 0) {
            $summary['avg_order_value'] = $summary['total_revenue'] / $summary['total_transactions'];
        }

        // Top 5 items
        $topItems = (clone $query)
            ->select('item', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(subtotal) as total_revenue'))
            ->groupBy('item')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();
            
        // Payment methods
        $paymentMethods = (clone $query)
            ->select('metode_bayar', DB::raw('COUNT(DISTINCT transaction_id) as total_trx'), DB::raw('SUM(subtotal) as total_revenue'))
            ->groupBy('metode_bayar')
            ->get();

        $pdf = Pdf::loadView('laporan.pdf', compact('transactions', 'periode', 'summary', 'topItems', 'paymentMethods'));
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf->download('laporan_penjualan_' . date('Ymd_His') . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(new TransactionsExport($request), 'laporan_penjualan_' . date('Ymd_His') . '.xlsx');
    }
}
