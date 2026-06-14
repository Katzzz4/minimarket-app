<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\User;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        $branches = Branch::all();
        $kasirs = User::role('Kasir')->get();

        foreach ($branches as $branch) {
            $kasir = $kasirs->where('branch_id', $branch->id)->first();
            if (!$kasir) continue;

            $products = Product::all();

            for ($i = 0; $i < 10; $i++) {
                $invoice = 'INV-' . $branch->id . '-' . str_pad($i + 1, 3, '0', STR_PAD_LEFT);

                $selectedProducts = $products->random(rand(2, 5));
                $total = 0;
                $items = [];

                foreach ($selectedProducts as $product) {
                    $qty = rand(1, 5);
                    $subtotal = $product->price * $qty;
                    $total += $subtotal;
                    $items[] = [
                        'product_id' => $product->id,
                        'quantity' => $qty,
                        'price' => $product->price,
                        'subtotal' => $subtotal,
                    ];
                }

                $paid = $total + rand(0, 5000);

                $transaction = Transaction::create([
                    'invoice_number' => $invoice,
                    'branch_id' => $branch->id,
                    'user_id' => $kasir->id,
                    'total' => $total,
                    'paid' => $paid,
                    'change' => $paid - $total,
                ]);

                foreach ($items as $item) {
                    TransactionItem::create(array_merge($item, ['transaction_id' => $transaction->id]));
                }
            }
        }
    }
}
