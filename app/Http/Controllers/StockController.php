<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $branchId = auth()->user()->branch_id;

        $movements = StockMovement::with('product', 'user')
            ->where('branch_id', $branchId)
            ->when($request->filled('type'), function ($q) use ($request) {
                $q->where('type', $request->type);
            })
            ->when($request->filled('start_date'), function ($q) use ($request) {
                $q->whereDate('created_at', '>=', $request->start_date);
            })
            ->when($request->filled('end_date'), function ($q) use ($request) {
                $q->whereDate('created_at', '<=', $request->end_date);
            })
            ->latest()
            ->paginate(15);

        $products = ProductStock::with('product')
            ->where('branch_id', $branchId)
            ->get();

        return view('stock.index', compact('movements', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'type' => 'required|in:in,out,adjustment',
            'quantity' => 'required|integer|min:1',
            'note' => 'nullable|string|max:255',
        ]);

        $branchId = auth()->user()->branch_id;

        DB::transaction(function () use ($validated, $branchId) {
            $stock = ProductStock::firstOrCreate(
                ['product_id' => $validated['product_id'], 'branch_id' => $branchId],
                ['quantity' => 0]
            );

            if ($validated['type'] === 'out' && $stock->quantity < $validated['quantity']) {
                abort(422, 'Stok tidak cukup.');
            }

            match ($validated['type']) {
                'in' => $stock->increment('quantity', $validated['quantity']),
                'out' => $stock->decrement('quantity', $validated['quantity']),
                'adjustment' => $stock->update(['quantity' => $validated['quantity']]),
            };

            StockMovement::create([
                ...$validated,
                'branch_id' => $branchId,
                'user_id' => auth()->id(),
            ]);
        });

        return redirect()->route('stock.index')->with('success', 'Stok berhasil diperbarui.');
    }
}