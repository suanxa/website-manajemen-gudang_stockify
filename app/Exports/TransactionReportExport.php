<?php

namespace App\Exports;

use App\Models\StockTransaction;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TransactionReportExport implements FromQuery, WithHeadings, WithMapping, WithStyles, WithEvents
{
    protected $filters;
    private $rowCount = 0;
    private $totalDiterima = 0;  
    private $totalDikeluarkan = 0; 

    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    public function query()
    {
        $query = StockTransaction::with(['user', 'product' => function($q) {
            $q->withTrashed()->with('supplier'); 
        }]);

        if (!empty($this->filters['start_date'])) {
            $query->whereDate('date', '>=', $this->filters['start_date']);
        }
        if (!empty($this->filters['end_date'])) {
            $query->whereDate('date', '<=', $this->filters['end_date']);
        }
        if (!empty($this->filters['type'])) {
            $query->where('type', $this->filters['type']);
        }
        if (!empty($this->filters['supplier_id'])) {
            $query->whereHas('product', function($q) {
                $q->where('supplier_id', $this->filters['supplier_id']);
            });
        }

        return $query->latest();
    }

    public function headings(): array
    {
        return [
            'Tanggal', 'Waktu', 'Nama Produk', 'SKU', 'Supplier', 'Tipe', 'Jumlah', 'Status', 'Petugas (Role)', 'Catatan'
        ];
    }

    public function map($trx): array
    {
        $this->rowCount++; 
        if ($trx->status == 'Diterima') {
            $this->totalDiterima += $trx->quantity;
        } elseif ($trx->status == 'Dikeluarkan') {
            $this->totalDikeluarkan += $trx->quantity;
        }

        return [
            $trx->date->format('d/m/Y'),
            $trx->created_at->format('H:i'),
            $trx->product->name ?? 'Produk Terhapus',
            $trx->product->sku ?? '-',
            $trx->product->supplier->name ?? '-',
            $trx->type,
            
            ($trx->status == 'Diterima' ? '+' : ($trx->status == 'Dikeluarkan' ? '-' : '')) . $trx->quantity,
            $trx->status,
            ($trx->user->name ?? 'System') . ' (' . ($trx->user->role ?? '-') . ')',
            $trx->notes ?? '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $summaryRow = $this->rowCount + 3; // Kasih jarak 2 baris kosong agar rapi

                // Styling Area Ringkasan
                $event->sheet->setCellValue('A' . $summaryRow, 'RINGKASAN LAPORAN (DATA TERKONFIRMASI):');
                
                // Baris Total Diterima
                $event->sheet->setCellValue('F' . ($summaryRow + 1), 'Total Barang Diterima (+):');
                $event->sheet->setCellValue('G' . ($summaryRow + 1), $this->totalDiterima);

                // Baris Total Dikeluarkan
                $event->sheet->setCellValue('F' . ($summaryRow + 2), 'Total Barang Dikeluarkan (-):');
                $event->sheet->setCellValue('G' . ($summaryRow + 2), $this->totalDikeluarkan);
                
                // Baris Netto (Selisih)
                $lastRow = $summaryRow + 3;
                $netto = $this->totalDiterima - $this->totalDikeluarkan;
                $event->sheet->setCellValue('F' . $lastRow, 'Netto Perubahan Stok:');
                $event->sheet->setCellValue('G' . $lastRow, $netto);

                $event->sheet->getStyle('A' . $summaryRow . ':G' . $lastRow)->getFont()->setBold(true);
                
                if ($netto >= 0) {
                    $event->sheet->getStyle('G' . $lastRow)->getFont()->getColor()->setRGB('16A34A');
                } else {
                    $event->sheet->getStyle('G' . $lastRow)->getFont()->getColor()->setRGB('DC2626'); 
                }

                foreach (range('A', 'J') as $columnID) {
                    $event->sheet->getColumnDimension($columnID)->setAutoSize(true);
                }
            },
        ];
    }
}