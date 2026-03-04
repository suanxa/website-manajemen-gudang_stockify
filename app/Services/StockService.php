<?php

namespace App\Services;

use App\Repositories\StockTransactionRepository;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\StockTransaction;
use Exception;

class StockService
{
    protected $stockRepo;

    public function __construct(StockTransactionRepository $stockRepo)
    {
        $this->stockRepo = $stockRepo;
    }

    public function getHistoryData()
    {
        return StockTransaction::with(['product', 'user'])
            ->latest()
            ->paginate(10); 
    }

    /**
     * Logika utama mencatat transaksi awal (Default: Pending)
     */
    public function processTransaction(array $data)
    {
        return DB::transaction(function () use ($data) {
            // Gunakan lockForUpdate() agar stok tidak berubah di tengah proses oleh user lain
            $product = Product::withTrashed()->lockForUpdate()->findOrFail($data['product_id']);
            
            $data['user_id'] = Auth::id();

            // Validasi stok jika ingin langsung 'Dikeluarkan'
            if ($data['type'] === 'Keluar' && isset($data['status']) && $data['status'] === 'Dikeluarkan') {
                if ($product->current_stock < $data['quantity']) {
                    throw new Exception("Stok tidak mencukupi! Sisa stok: " . $product->current_stock);
                }
            }

            // Default status ke Pending jika tidak ditentukan
            $data['status'] = $data['status'] ?? 'Pending';

            // 1. Simpan Transaksi
            $transaction = $this->stockRepo->store($data);

            // 2. Update Stok (Hanya berjalan jika status Diterima/Dikeluarkan)
            $this->applyStockUpdate($transaction, $product);

            return $transaction;
        });
    }

    /**
     * Logika Konfirmasi oleh Staff (Pending -> Diterima/Dikeluarkan)
     */
    public function confirmTransaction($id, $newStatus)
    {
        return DB::transaction(function () use ($id, $newStatus) {
            $transaction = StockTransaction::lockForUpdate()->findOrFail($id);
            $product = Product::withTrashed()->lockForUpdate()->findOrFail($transaction->product_id);

            if ($transaction->status !== 'Pending') {
                throw new Exception("Transaksi ini sudah diproses (Status: {$transaction->status}).");
            }

            // 1. Jika statusnya Ditolak, cukup update status dan selesai.
            if ($newStatus === 'Ditolak') {
                $transaction->update(['status' => 'Ditolak']);
                return $transaction; 
            }

            // 2. Validasi stok hanya untuk transaksi yang akan Dikeluarkan
            if ($newStatus === 'Dikeluarkan' && $transaction->type === 'Keluar') {
                if ($product->current_stock < $transaction->quantity) {
                    throw new Exception("Konfirmasi Gagal! Stok fisik saat ini ({$product->current_stock}) tidak cukup.");
                }
            }

            // 3. Update status jadi Diterima/Dikeluarkan
            $transaction->update(['status' => $newStatus]);

            // 4. Eksekusi perubahan stok
            $this->applyStockUpdate($transaction, $product);

            return $transaction;
        });
    }

    /**
     * Helper Fungsi untuk Update Stok Fisik
     */
    private function applyStockUpdate($transaction, $product)
    {
        if ($transaction->status === 'Diterima' && $transaction->type === 'Masuk') {
            $product->increment('current_stock', $transaction->quantity);
        } 
        elseif ($transaction->status === 'Dikeluarkan' && $transaction->type === 'Keluar') {
            // Pastikan stok tidak menjadi negatif (Double Check)
            if ($product->current_stock >= $transaction->quantity) {
                $product->decrement('current_stock', $transaction->quantity);
            } else {
                throw new Exception("Gagal update stok: Stok tidak mencukupi.");
            }
        }
    }

    /**
     * Logika Stock Opname
     */
    public function processOpname(array $data)
    {
        return DB::transaction(function () use ($data) {
            $product = Product::withTrashed()->lockForUpdate()->findOrFail($data['product_id']);
            
            $systemStock = $product->current_stock;
            $physicalStock = (int)$data['physical_stock'];
            $difference = $physicalStock - $systemStock;

            if ($difference == 0) {
                throw new Exception("Jumlah fisik sama dengan sistem. Tidak ada penyesuaian yang diperlukan.");
            }

            // Catat hasil opname sebagai transaksi yang langsung SELESAI
            $transaction = $this->stockRepo->store([
                'product_id' => $product->id,
                'user_id'    => Auth::id(),
                'type'       => ($difference > 0) ? 'Masuk' : 'Keluar',
                'quantity'   => abs($difference),
                'date'       => now(),
                'status'     => ($difference > 0) ? 'Diterima' : 'Dikeluarkan',
                'notes'      => "STOCK OPNAME: Sistem $systemStock, Fisik $physicalStock. Selisih $difference. Ket: " . ($data['notes'] ?? '-'),
            ]);

            // Sinkronisasi stok fisik
            $product->current_stock = $physicalStock;
            $product->save();

            return $transaction;
        });
    }
}