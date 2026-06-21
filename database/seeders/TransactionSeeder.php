<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        $branches = Branch::all();

        foreach ($branches as $branch) {
            $kasir = User::where('branch_id', $branch->id)
                ->whereHas('roles', fn($q) => $q->where('name', 'Kasir'))
                ->first();

            if (!$kasir) continue;

            // Buat 10 transaksi per cabang
            for ($i = 0; $i < 10; $i++) {
                $products = Product::inRandomOrder()->take(rand(2, 5))->get();

                $total = 0;
                $itemsData = [];

                foreach ($products as $product) {
                    $qty = rand(1, 3);
                    $stock = ProductStock::where('product_id', $product->id)
                        ->where('branch_id', $branch->id)
                        ->first();

                    if (!$stock || $stock->quantity < $qty) continue;

                    $subtotal = $product->price * $qty;
                    $total += $subtotal;

                    $itemsData[] = [
                        'product_id' => $product->id,
                        'quantity'   => $qty,
                        'price'      => $product->price,
                        'subtotal'   => $subtotal,
                    ];

                    $stock->decrement('quantity', $qty);
                }

                if (empty($itemsData) || $total === 0) continue;

                $paid = ceil($total / 10000) * 10000 + rand(0, 2) * 5000;

                $transaction = Transaction::create([
                    'invoice_number' => 'INV-' . now()->subDays(rand(0, 7))->format('Ymd') . '-' . Str::upper(Str::random(6)),
                    'branch_id'      => $branch->id,
                    'user_id'        => $kasir->id,
                    'total'          => $total,
                    'paid'           => $paid,
                    'change'         => $paid - $total,
                    'created_at'     => now()->subDays(rand(0, 7))->subHours(rand(0, 8)),
                ]);

                foreach ($itemsData as $data) {
                    $transaction->items()->create($data);
                }
            }
        }
    }
}