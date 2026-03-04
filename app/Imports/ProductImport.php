<?php

namespace App\Imports;

use App\Models\Product;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation; // Tambahkan interface ini

class ProductImport implements ToCollection, WithHeadingRow, WithValidation
{
    public function headingRow(): int
    {
        return 1;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Kita pakai updateOrCreate supaya kalau SKU sudah ada, dia update data lama
            // Kalau SKU baru, dia buat data baru (Gacor buat sinkronisasi stok)
            Product::updateOrCreate(
                ['sku' => trim($row['sku'])],
                [
                    'name'           => trim($row['name']),
                    'category_id'    => $row['category_id'],
                    'supplier_id'    => $row['supplier_id'],
                    'purchase_price' => $row['purchase_price'],
                    'selling_price'  => $row['selling_price'],
                    'current_stock'  => $row['current_stock'] ?? 0,
                    'minimum_stock'  => $row['minimum_stock'] ?? 0,
                    'description'    => $row['description'] ?? null,
                ]
            );
        }
    }

    /**
     * ATURAN VALIDASI (Gacor Prevention)
     * Ini yang bakal cegah error SQLSTATE: Integrity constraint violation
     */
    public function rules(): array
    {
        return [
            'name'           => 'required|string|max:255',
            'sku'            => 'required|string',
            'category_id'    => 'required|exists:categories,id', // Cek apakah ID ada di tabel categories
            'supplier_id'    => 'required|exists:suppliers,id',  // Cek apakah ID ada di tabel suppliers
            'purchase_price' => 'required|numeric|min:0',
            'selling_price'  => 'required|numeric|min:0',
        ];
    }

    /**
     * PESAN ERROR CUSTOM (Bahasa Indonesia)
     */
    public function customValidationMessages()
    {
        return [
            'category_id.exists' => 'Waduh Bos, ID Kategori :input nggak ada di database!',
            'supplier_id.exists' => 'ID Supplier :input belum terdaftar, cek lagi ya.',
            'sku.required'       => 'SKU nggak boleh kosong di Excelnya.',
        ];
    }
}