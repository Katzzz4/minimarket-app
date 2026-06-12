<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeederData extends Seeder
{
    public function run(): void
    {
        $branch = Branch::create([
            'name' => 'Cabang Pusat',
            'city' => 'Bandung',
        ]);

        $owner = User::create([
            'name' => 'Pak Jayusman',
            'email' => 'owner@minimarket.test',
            'password' => 'password',
            'branch_id' => $branch->id,
        ]);

        $owner->assignRole('Owner');
    }
}