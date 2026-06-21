<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Makanan Pokok',
            'Minuman',
            'Snack & Camilan',
            'Kebutuhan Rumah',
            'Perawatan Diri',
            'Bumbu & Rempah',
        ];

        foreach ($categories as $name) {
            Category::firstOrCreate(['name' => $name]);
        }
    }
}