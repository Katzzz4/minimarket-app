<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;
use App\Models\ProductStock;
use App\Models\User;
use App\Models\Transaction;

class BranchController extends Controller
{
    public function index()
    {
        $branches = Branch::latest()->paginate(10);
        return view('branches.index', compact('branches'));
    }

    public function create()
    {
        return view('branches.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
        ]);

        Branch::create($validated);

        return redirect()->route('branches.index')->with('success', 'Cabang berhasil ditambahkan.');
    }

    public function edit(Branch $branch)
    {
        return view('branches.edit', compact('branch'));
    }

    public function update(Request $request, Branch $branch)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
        ]);

        $branch->update($validated);

        return redirect()->route('branches.index')->with('success', 'Cabang berhasil diperbarui.');
    }

    public function show(Branch $branch)
    {
        $stocks = ProductStock::with('product.category')
            ->where('branch_id', $branch->id)
            ->get();

        $employees = User::with('roles')
            ->where('branch_id', $branch->id)
            ->get();

        // Penjualan
        $totalSalesToday = Transaction::where('branch_id', $branch->id)
            ->whereDate('created_at', today())
            ->sum('total');

        $totalTransactionsToday = Transaction::where('branch_id', $branch->id)
            ->whereDate('created_at', today())
            ->count();

        $totalSalesMonth = Transaction::where('branch_id', $branch->id)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total');

        $totalSalesAll = Transaction::where('branch_id', $branch->id)
            ->sum('total');

        $recentTransactions = Transaction::with('user')
            ->where('branch_id', $branch->id)
            ->latest()
            ->take(5)
            ->get();

        return view('branches.show', compact(
            'branch',
            'stocks',
            'employees',
            'totalSalesToday',
            'totalTransactionsToday',
            'totalSalesMonth',
            'totalSalesAll',
            'recentTransactions'
        ));
    }

    public function destroy(Branch $branch)
    {
        $branch->delete();
        return redirect()->route('branches.index')->with('success', 'Cabang berhasil dihapus.');
    }
}