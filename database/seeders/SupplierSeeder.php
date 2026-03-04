<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        Supplier::create([
            'name' => 'PT. Global Teknologi',
            'phone' => '08123456789',
            'address' => 'Jl. Sudirman No. 123, Jakarta',
            'email' => 'contact@globaltekno.com',
        ]);
    }
}