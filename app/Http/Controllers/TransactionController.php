<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductStock;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'paid' => 'required|numeric|min:0',
        ]);

        $branchId = auth()->user()->branch_id;

        return DB::transaction(function () use ($validated, $branchId) {
            $total = 0;
            $itemsData = [];

            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);
                $stock = ProductStock::where('product_id', $product->id)
                    ->where('branch_id', $branchId)
                    ->first();

                if (!$stock || $stock->quantity < $item['quantity']) {
                    abort(422, "Stok {$product->name} tidak cukup.");
                }

                $subtotal = $product->price * $item['quantity'];
                $total += $subtotal;

                $itemsData[] = [
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                    'subtotal' => $subtotal,
                ];

                $stock->decrement('quantity', $item['quantity']);
            }

            if ($validated['paid'] < $total) {
                abort(422, 'Pembayaran kurang dari total.');
            }

            $transaction = Transaction::create([
                'invoice_number' => 'INV-' . now()->format('Ymd') . '-' . Str::upper(Str::random(6)),
                'branch_id' => $branchId,
                'user_id' => auth()->id(),
                'total' => $total,
                'paid' => $validated['paid'],
                'change' => $validated['paid'] - $total,
            ]);

            foreach ($itemsData as $data) {
                $transaction->items()->create($data);
            }

            return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil. Invoice: ' . $transaction->invoice_number);
        });
    }
    public function index(Request $request)
    {
        $transactions = Transaction::with('branch', 'user')
            ->when(auth()->user()->hasRole('Manajer Toko'), function ($q) {
                $q->where('branch_id', auth()->user()->branch_id);
            })
            ->when(auth()->user()->hasRole('Kasir'), function ($q) {
                $q->where('branch_id', auth()->user()->branch_id)
                    ->where('user_id', auth()->id());
            })
            ->latest()
            ->paginate(15);

        return view('transactions.index', compact('transactions'));
    }
}