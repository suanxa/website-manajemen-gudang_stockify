<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductTemplateExport implements FromArray, WithHeadings
{
    public function array(): array
    {
        return [
            [
                'Contoh Produk',
                'IP 01',
                1,
                1,
                100000,
                120000,
                10,
                5,
                'Contoh deskripsi produk',
            ]
        ];
    }

    public function headings(): array
    {
        return [
            'name',
            'sku',
            'category_id',
            'supplier_id',
            'purchase_price',
            'selling_price',
            'current_stock',
            'minimum_stock',
            'description',
        ];
    }
}
