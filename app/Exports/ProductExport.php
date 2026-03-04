<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Product::all([
            'name',
            'sku',
            'category_id',
            'supplier_id',
            'purchase_price',
            'selling_price',
            'current_stock',
            'minimum_stock',
            'description',
        ]);
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
