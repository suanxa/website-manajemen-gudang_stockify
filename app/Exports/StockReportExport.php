<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles; 
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet; 

class StockReportExport implements FromQuery, WithHeadings, WithMapping, WithStyles
{
    protected $categoryId;
    protected $search;
    private $rowCount = 0; 

    public function __construct($categoryId = null, $search = null)
    {
        $this->categoryId = $categoryId;
        $this->search = $search;
    }

    public function query()
    {
        $query = Product::with('category');

        if ($this->categoryId) {
            $query->where('category_id', $this->categoryId);
        }

        if ($this->search) {
            $search = $this->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        return $query->orderBy('current_stock', 'asc');
    }

    public function headings(): array
    {
        return ['Nama Produk', 'SKU', 'Kategori', 'Stok Saat Ini', 'Status'];
    }

    public function map($product): array
    {
        $this->rowCount++; 

        return [
            $product->name,
            $product->sku,
            $product->category->name ?? '-',
            $product->current_stock,
            $product->current_stock <= $product->minimum_stock ? 'KRITIS' : 'AMAN',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // 1. Buat Header (Baris 1) jadi Bold
        $sheet->getStyle('1')->getFont()->setBold(true);

        // 2. Loop data untuk mewarnai kolom Status (Kolom E)
        for ($i = 2; $i <= ($this->rowCount + 1); $i++) {
            $statusValue = $sheet->getCell('E' . $i)->getValue();

            if ($statusValue === 'KRITIS') {
                $sheet->getStyle('E' . $i)->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'DC2626'] 
                    ]
                ]);
            } elseif ($statusValue === 'AMAN') {
                $sheet->getStyle('E' . $i)->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '16A34A'] 
                    ]
                ]);
            }
        }

        // Set auto-size biar kolomnya pas dengan panjang teks
        foreach (range('A', 'E') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
    }
}