<?php

namespace App\Repositories;

use App\Models\StockTransaction;

class StockTransactionRepository
{
    /**
     * Mengambil riwayat transaksi
     */
    public function getAllHistory()
    {
        
        return StockTransaction::with(['product' => function($query) {
                $query->withTrashed();
            }, 'user'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Menyimpan data transaksi baru
     */
    public function store(array $data)
    {
        return StockTransaction::create($data);
    }

    /**
     * Mencari transaksi berdasarkan ID
     */
    public function findById($id)
    {
        return StockTransaction::findOrFail($id);
    }

    /**
     * Update status transaksi
     */
    public function updateStatus($id, $status)
    {
        $transaction = $this->findById($id);
        $transaction->update(['status' => $status]);
        return $transaction;
    }
}