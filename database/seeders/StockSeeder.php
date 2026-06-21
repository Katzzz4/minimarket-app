<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Database\Seeder;

class StockSeeder extends Seeder
{
    public function run(): void
    {
        $branches = Branch::all();
        $products = Product::all();

        // Stok awal berbeda per cabang
        $stockConfig = [
            'JayuMart Pusat'   => ['min' => 50, 'max' => 150],
            'JayuMart Dago'    => ['min' => 30, 'max' => 100],
            'JayuMart Cimahi'  => ['min' => 20, 'max' => 80],
            'JayuMart Soreang' => ['min' => 15, 'max' => 60],
            'JayuMart Lembang' => ['min' => 10, 'max' => 50],
        ];

        foreach ($branches as $branch) {
            $config = $stockConfig[$branch->name] ?? ['min' => 10, 'max' => 50];

            // Ambil user gudang cabang ini untuk dicatat di stock_movements
            $gudangUser = User::where('branch_id', $branch->id)
                ->whereHas('roles', fn($q) => $q->where('name', 'Pegawai Gudang'))
                ->first();

            foreach ($products as $product) {
                $qty = rand($config['min'], $config['max']);

                // Beberapa produk sengaja dibuat stok menipis untuk demo
                if (rand(1, 10) === 1) {
                    $qty = rand(1, 4);
                }

                ProductStock::firstOrCreate(
                    ['product_id' => $product->id, 'branch_id' => $branch->id],
                    ['quantity' => $qty, 'min_stock' => 5]
                );

                // Catat sebagai stock movement awal
                if ($gudangUser) {
                    StockMovement::create([
                        'product_id' => $product->id,
                        'branch_id'  => $branch->id,
                        'user_id'    => $gudangUser->id,
                        'type'       => 'in',
                        'quantity'   => $qty,
                        'note'       => 'Stok awal',
                    ]);
                }
            }
        }
    }
}