<?php

namespace App\Http\Controllers;

use App\Models\StockMovement;
use App\Models\Transaction;
use App\Exports\TransactionsExport;
use App\Exports\StockMovementsExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    private function transactionQuery(Request $request)
    {
        $query = Transaction::with('branch', 'user', 'items.product')
            ->when(auth()->user()->hasRole('Manajer Toko'), function ($q) {
                $q->where('branch_id', auth()->user()->branch_id);
            })
            ->when($request->filled('branch_id'), function ($q) use ($request) {
                $q->where('branch_id', $request->branch_id);
            })
            ->when($request->filled('start_date'), function ($q) use ($request) {
                $q->whereDate('created_at', '>=', $request->start_date);
            })
            ->when($request->filled('end_date'), function ($q) use ($request) {
                $q->whereDate('created_at', '<=', $request->end_date);
            });

        return $query;
    }

    private function stockQuery(Request $request)
    {
        $query = StockMovement::with('branch', 'product', 'user')
            ->when(auth()->user()->hasRole('Manajer Toko'), function ($q) {
                $q->where('branch_id', auth()->user()->branch_id);
            })
            ->when($request->filled('branch_id'), function ($q) use ($request) {
                $q->where('branch_id', $request->branch_id);
            })
            ->when($request->filled('type'), function ($q) use ($request) {
                $q->where('type', $request->type);
            })
            ->when($request->filled('start_date'), function ($q) use ($request) {
                $q->whereDate('created_at', '>=', $request->start_date);
            })
            ->when($request->filled('end_date'), function ($q) use ($request) {
                $q->whereDate('created_at', '<=', $request->end_date);
            });

        return $query;
    }

    public function transactions(Request $request)
    {
        $transactions = $this->transactionQuery($request)->latest()->paginate(20);
        return view('reports.transactions', compact('transactions'));
    }

    public function stock(Request $request)
    {
        $movements = $this->stockQuery($request)->latest()->paginate(20);
        return view('reports.stock', compact('movements'));
    }

    public function exportTransactionsPdf(Request $request)
    {
        $transactions = $this->transactionQuery($request)->latest()->get();

        $pdf = Pdf::loadView('reports.pdf.transactions', compact('transactions'));
        return $pdf->download('laporan-transaksi-' . now()->format('Ymd_His') . '.pdf');
    }

    public function exportTransactionsExcel(Request $request)
    {
        $transactions = $this->transactionQuery($request)->latest()->get();

        return Excel::download(
            new TransactionsExport($transactions),
            'laporan-transaksi-' . now()->format('Ymd_His') . '.xlsx'
        );
    }

    public function exportStockPdf(Request $request)
    {
        $movements = $this->stockQuery($request)->latest()->get();

        $pdf = Pdf::loadView('reports.pdf.stock', compact('movements'));
        return $pdf->download('laporan-stok-' . now()->format('Ymd_His') . '.pdf');
    }

    public function exportStockExcel(Request $request)
    {
        $movements = $this->stockQuery($request)->latest()->get();

        return Excel::download(
            new StockMovementsExport($movements),
            'laporan-stok-' . now()->format('Ymd_His') . '.xlsx'
        );
    }
}