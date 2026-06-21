<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Owner
        $owner = User::firstOrCreate(
            ['email' => 'owner@jayumart.test'],
            [
                'name' => 'Pak Jayusman',
                'password' => 'password',
                'branch_id' => Branch::first()->id,
            ]
        );
        $owner->assignRole('Owner');

        // Pegawai per cabang
        $branches = Branch::all();

        $employees = [
            ['role' => 'Manajer Toko', 'prefix' => 'manajer'],
            ['role' => 'Supervisor',   'prefix' => 'supervisor'],
            ['role' => 'Kasir',        'prefix' => 'kasir'],
            ['role' => 'Pegawai Gudang', 'prefix' => 'gudang'],
        ];

        foreach ($branches as $branch) {
            $slug = strtolower(str_replace(' ', '', $branch->name));

            foreach ($employees as $emp) {
                $email = $emp['prefix'] . '.' . $slug . '@jayumart.test';

                $user = User::firstOrCreate(
                    ['email' => $email],
                    [
                        'name' => $emp['role'] . ' ' . $branch->name,
                        'password' => 'password',
                        'branch_id' => $branch->id,
                    ]
                );
                $user->syncRoles([$emp['role']]);
            }
        }
    }
}