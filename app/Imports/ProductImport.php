<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\StockTransaction;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ProductImport implements ToCollection, WithHeadingRow, WithValidation
{
    public function headingRow(): int
    {
        return 1;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $sku = trim($row['sku']);
            $quantityInExcel = $row['current_stock'] ?? 0;

            // 1. Proses Simpan/Update Produk
            // PERHATIKAN: 'current_stock' TIDAK dimasukkan di sini agar tidak double hitung
            $product = Product::updateOrCreate(
                ['sku' => $sku],
                [
                    'name'           => trim($row['name']),
                    'category_id'    => $row['category_id'],
                    'supplier_id'    => $row['supplier_id'],
                    'purchase_price' => $row['purchase_price'],
                    'selling_price'  => $row['selling_price'],
                    // JANGAN masukkan current_stock di sini. 
                    // Biarkan nilainya tetap sesuai database (biasanya default 0 untuk produk baru)
                    'minimum_stock'  => $row['minimum_stock'] ?? 0,
                    'description'    => $row['description'] ?? null,
                ]
            );

            // 2. Catat Transaksi dengan status Pending
            if ($quantityInExcel > 0) {
                StockTransaction::create([
                    'product_id' => $product->id,
                    'user_id'    => Auth::id() ?? 1,
                    'type'       => 'Masuk',
                    'quantity'   => $quantityInExcel,
                    'date'       => now(),
                    'status'     => 'Pending', // Menunggu konfirmasi di menu transaksi
                    'notes'      => 'Import data via Excel - Menunggu verifikasi fisik',
                ]);
            }
        }
    }

    public function rules(): array
    {
        return [
            'name'           => 'required|string|max:255',
            'sku'            => 'required|string',
            'category_id'    => 'required|exists:categories,id',
            'supplier_id'    => 'required|exists:suppliers,id',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price'  => 'required|numeric|min:0',
            'current_stock'  => 'required|numeric|min:0',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'category_id.exists' => 'Waduh Bos, ID Kategori :input nggak ada di database!',
            'supplier_id.exists' => 'ID Supplier :input belum terdaftar, cek lagi ya.',
            'sku.required'       => 'SKU nggak boleh kosong di Excelnya.',
        ];
    }
}