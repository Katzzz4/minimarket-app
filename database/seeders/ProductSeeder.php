<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductStock;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Makanan' => ['Indomie Goreng', 'Beras 5kg', 'Minyak Goreng 1L', 'Gula Pasir 1kg'],
            'Minuman' => ['Aqua 600ml', 'Teh Botol', 'Kopi Sachet', 'Susu UHT 1L'],
            'Kebutuhan Rumah' => ['Sabun Mandi', 'Sampo', 'Deterjen', 'Tissue'],
        ];

        $branches = Branch::all();

        foreach ($categories as $categoryName => $products) {
            $category = Category::firstOrCreate(['name' => $categoryName]);

            foreach ($products as $index => $productName) {
                $product = Product::create([
                    'name' => $productName,
                    'sku' => 'SKU-' . strtoupper(substr($categoryName, 0, 3)) . '-' . ($index + 1),
                    'category_id' => $category->id,
                    'price' => rand(2000, 50000),
                    'cost_price' => rand(1500, 40000),
                ]);

                foreach ($branches as $branch) {
                    ProductStock::create([
                        'product_id' => $product->id,
                        'branch_id' => $branch->id,
                        'quantity' => rand(10, 100),
                        'min_stock' => 5,
                    ]);
                }
            }
        }
    }
}