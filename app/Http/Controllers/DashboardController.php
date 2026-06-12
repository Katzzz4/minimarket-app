<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\Transaction;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->hasRole('Owner')) {
            return $this->ownerDashboard();
        }

        return $this->branchDashboard($user->branch_id);
    }

    private function ownerDashboard()
    {
        $today = Carbon::today();

        // total penjualan hari ini per cabang
        $salesToday = Transaction::whereDate('created_at', $today)
            ->selectRaw('branch_id, SUM(total) as total_sales, COUNT(*) as total_transactions')
            ->groupBy('branch_id')
            ->get()
            ->keyBy('branch_id');

        $branches = Branch::all()->map(function ($branch) use ($salesToday) {
            $sales = $salesToday->get($branch->id);

            return [
                'id' => $branch->id,
                'name' => $branch->name,
                'city' => $branch->city,
                'total_sales_today' => $sales?->total_sales ?? 0,
                'total_transactions_today' => $sales?->total_transactions ?? 0,
                'low_stock_count' => ProductStock::where('branch_id', $branch->id)
                    ->whereColumn('quantity', '<=', 'min_stock')
                    ->count(),
            ];
        });

        $summary = [
            'total_branches' => Branch::count(),
            'total_sales_today' => $salesToday->sum('total_sales'),
            'total_transactions_today' => $salesToday->sum('total_transactions'),
            'total_low_stock' => $branches->sum('low_stock_count'),
        ];

        $recentTransactions = Transaction::with('branch', 'user')
            ->latest()
            ->take(10)
            ->get();

        return view('dashboard.owner', compact('summary', 'branches', 'recentTransactions'));
    }

    private function branchDashboard($branchId)
    {
        $today = Carbon::today();

        $summary = [
            'total_sales_today' => Transaction::where('branch_id', $branchId)
                ->whereDate('created_at', $today)
                ->sum('total'),
            'total_transactions_today' => Transaction::where('branch_id', $branchId)
                ->whereDate('created_at', $today)
                ->count(),
            'low_stock_products' => ProductStock::with('product')
                ->where('branch_id', $branchId)
                ->whereColumn('quantity', '<=', 'min_stock')
                ->get(),
        ];

        $recentTransactions = Transaction::with('user')
            ->where('branch_id', $branchId)
            ->latest()
            ->take(10)
            ->get();

        return view('dashboard.branch', compact('summary', 'recentTransactions'));
    }
}