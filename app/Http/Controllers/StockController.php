<?php

namespace App\Http\Controllers;

use App\Models\ProductStock;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
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

        return redirect()->back()->with('success', 'Stok berhasil diperbarui.');
    }
}