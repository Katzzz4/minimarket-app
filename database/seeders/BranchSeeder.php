<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    public function run(): void
    {
        $branches = [
            ['name' => 'JayuMart Pusat', 'city' => 'Bandung', 'address' => 'Jl. Asia Afrika No. 10, Bandung'],
            ['name' => 'JayuMart Dago', 'city' => 'Bandung', 'address' => 'Jl. Ir. H. Juanda No. 45, Dago'],
            ['name' => 'JayuMart Cimahi', 'city' => 'Cimahi', 'address' => 'Jl. Raya Cimahi No. 88'],
            ['name' => 'JayuMart Soreang', 'city' => 'Soreang', 'address' => 'Jl. Raya Soreang No. 12'],
            ['name' => 'JayuMart Lembang', 'city' => 'Lembang', 'address' => 'Jl. Raya Lembang No. 67'],
        ];

        foreach ($branches as $branch) {
            Branch::firstOrCreate(['name' => $branch['name']], $branch);
        }
    }
}