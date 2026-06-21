<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            // Makanan Pokok
            ['name' => 'Beras Premium 5kg',     'sku' => 'MKN-001', 'category' => 'Makanan Pokok', 'price' => 72000,  'cost' => 63000],
            ['name' => 'Beras Medium 5kg',       'sku' => 'MKN-002', 'category' => 'Makanan Pokok', 'price' => 58000,  'cost' => 50000],
            ['name' => 'Minyak Goreng 1L',       'sku' => 'MKN-003', 'category' => 'Makanan Pokok', 'price' => 18500,  'cost' => 16000],
            ['name' => 'Minyak Goreng 2L',       'sku' => 'MKN-004', 'category' => 'Makanan Pokok', 'price' => 35000,  'cost' => 30000],
            ['name' => 'Gula Pasir 1kg',         'sku' => 'MKN-005', 'category' => 'Makanan Pokok', 'price' => 14500,  'cost' => 12500],
            ['name' => 'Tepung Terigu 1kg',      'sku' => 'MKN-006', 'category' => 'Makanan Pokok', 'price' => 11000,  'cost' => 9500],
            ['name' => 'Indomie Goreng',          'sku' => 'MKN-007', 'category' => 'Makanan Pokok', 'price' => 3500,   'cost' => 2800],
            ['name' => 'Indomie Kuah Ayam',      'sku' => 'MKN-008', 'category' => 'Makanan Pokok', 'price' => 3500,   'cost' => 2800],
            ['name' => 'Mie Sedaap Goreng',      'sku' => 'MKN-009', 'category' => 'Makanan Pokok', 'price' => 3200,   'cost' => 2600],
            ['name' => 'Telur Ayam 1kg',         'sku' => 'MKN-010', 'category' => 'Makanan Pokok', 'price' => 28000,  'cost' => 24000],

            // Minuman
            ['name' => 'Aqua 600ml',             'sku' => 'MIN-001', 'category' => 'Minuman', 'price' => 4000,   'cost' => 2800],
            ['name' => 'Aqua 1500ml',            'sku' => 'MIN-002', 'category' => 'Minuman', 'price' => 6500,   'cost' => 5000],
            ['name' => 'Teh Botol Sosro 350ml',  'sku' => 'MIN-003', 'category' => 'Minuman', 'price' => 5500,   'cost' => 4200],
            ['name' => 'Kopi Kapal Api Sachet',  'sku' => 'MIN-004', 'category' => 'Minuman', 'price' => 2500,   'cost' => 1800],
            ['name' => 'Susu Ultra 250ml',       'sku' => 'MIN-005', 'category' => 'Minuman', 'price' => 6000,   'cost' => 4800],
            ['name' => 'Susu Bendera 1L',        'sku' => 'MIN-006', 'category' => 'Minuman', 'price' => 18000,  'cost' => 15000],
            ['name' => 'Pocari Sweat 500ml',     'sku' => 'MIN-007', 'category' => 'Minuman', 'price' => 8500,   'cost' => 6800],

            // Snack
            ['name' => 'Chitato 68gr',           'sku' => 'SNK-001', 'category' => 'Snack & Camilan', 'price' => 10000, 'cost' => 8000],
            ['name' => 'Oreo Original',          'sku' => 'SNK-002', 'category' => 'Snack & Camilan', 'price' => 8500,  'cost' => 6800],
            ['name' => 'Roma Kelapa',            'sku' => 'SNK-003', 'category' => 'Snack & Camilan', 'price' => 7000,  'cost' => 5500],
            ['name' => 'Tango Wafer',            'sku' => 'SNK-004', 'category' => 'Snack & Camilan', 'price' => 6500,  'cost' => 5000],

            // Kebutuhan Rumah
            ['name' => 'Rinso 900gr',            'sku' => 'KEB-001', 'category' => 'Kebutuhan Rumah', 'price' => 22000, 'cost' => 18500],
            ['name' => 'Sunlight 755ml',         'sku' => 'KEB-002', 'category' => 'Kebutuhan Rumah', 'price' => 16000, 'cost' => 13000],
            ['name' => 'Softex 10pcs',           'sku' => 'KEB-003', 'category' => 'Kebutuhan Rumah', 'price' => 12000, 'cost' => 9500],
            ['name' => 'Tissue Paseo 250gr',     'sku' => 'KEB-004', 'category' => 'Kebutuhan Rumah', 'price' => 14000, 'cost' => 11500],

            // Perawatan Diri
            ['name' => 'Sabun Lifebuoy 110gr',  'sku' => 'PRW-001', 'category' => 'Perawatan Diri', 'price' => 5500,  'cost' => 4200],
            ['name' => 'Shampo Pantene 170ml',  'sku' => 'PRW-002', 'category' => 'Perawatan Diri', 'price' => 18000, 'cost' => 14500],
            ['name' => 'Pasta Gigi Pepsodent',  'sku' => 'PRW-003', 'category' => 'Perawatan Diri', 'price' => 11000, 'cost' => 8500],
            ['name' => 'Deodorant Rexona',      'sku' => 'PRW-004', 'category' => 'Perawatan Diri', 'price' => 16000, 'cost' => 13000],

            // Bumbu
            ['name' => 'Kecap Bango 220ml',     'sku' => 'BMB-001', 'category' => 'Bumbu & Rempah', 'price' => 10000, 'cost' => 8000],
            ['name' => 'Saus Sambal ABC 335ml', 'sku' => 'BMB-002', 'category' => 'Bumbu & Rempah', 'price' => 12000, 'cost' => 9500],
            ['name' => 'Royco Ayam 115gr',      'sku' => 'BMB-003', 'category' => 'Bumbu & Rempah', 'price' => 5500,  'cost' => 4200],
        ];

        foreach ($products as $data) {
            $category = Category::where('name', $data['category'])->first();
            Product::firstOrCreate(
                ['sku' => $data['sku']],
                [
                    'name' => $data['name'],
                    'category_id' => $category?->id,
                    'price' => $data['price'],
                    'cost_price' => $data['cost'],
                ]
            );
        }
    }
}