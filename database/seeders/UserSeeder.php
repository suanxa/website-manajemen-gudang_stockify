<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ADMIN
        User::create([
            'name' => 'Admin Stockify',
            'email' => 'admin@stockify.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // MANAGER GUDANG
        User::create([
            'name' => 'Manager Gudang',
            'email' => 'manager@stockify.test',
            'password' => Hash::make('password'),
            'role' => 'manager',
        ]);

        // STAFF GUDANG
        User::create([
            'name' => 'Staff Gudang',
            'email' => 'staff@stockify.test',
            'password' => Hash::make('password'),
            'role' => 'staff',
        ]);
    }
}
