<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoUserSeeder extends Seeder
{
    public function run(): void
    {
        $branches = Branch::all();

        if ($branches->isEmpty()) {
            $branches = collect([
                Branch::create(['name' => 'Cabang Dago', 'city' => 'Bandung']),
                Branch::create(['name' => 'Cabang Buah Batu', 'city' => 'Bandung']),
                Branch::create(['name' => 'Cabang Cimahi', 'city' => 'Cimahi']),
                Branch::create(['name' => 'Cabang Soreang', 'city' => 'Soreang']),
                Branch::create(['name' => 'Cabang Lembang', 'city' => 'Lembang']),
            ]);
        }

        $roles = ['Manajer Toko', 'Supervisor', 'Kasir', 'Pegawai Gudang'];

        foreach ($branches as $i => $branch) {
            foreach ($roles as $role) {
                $email = strtolower(str_replace(' ', '', $role)) . $branch->id . '@minimarket.test';

                $user = User::create([
                    'name' => "$role - {$branch->name}",
                    'email' => $email,
                    'password' => 'password',
                    'branch_id' => $branch->id,
                ]);

                $user->assignRole($role);
            }
        }
    }
}