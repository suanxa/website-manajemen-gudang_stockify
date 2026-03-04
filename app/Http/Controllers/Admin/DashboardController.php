<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockTransaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Data Utama (Tetap)
        $totalProducts = Product::count();

        // 2. Transaksi 30 Hari (Tetap)
        $totalIncoming = StockTransaction::where('type', 'Masuk')
            ->where('status', 'Diterima')
            ->where('date', '>=', now()->subDays(30))
            ->sum('quantity');
                
        $totalOutgoing = StockTransaction::where('type', 'Keluar')
            ->where('status', 'Dikeluarkan')
            ->where('date', '>=', now()->subDays(30))
            ->sum('quantity');

        // 3. Aktivitas (Tetap)
        $recentActivities = StockTransaction::with(['user', 'product' => fn($q) => $q->withTrashed()])
            ->latest()->take(5)->get();

        // 4. Grafik Stok Terbanyak (Tetap)
        $chartData = Product::orderBy('current_stock', 'desc')->take(5)->get();

        // 5. DATA UNTUK GRAFIK
        $last7Days = collect(range(6, 0))->map(function($i) {
            return now()->subDays($i)->format('Y-m-d');
        });

        // Mapping Hari Bahasa Indonesia
        $hariIndo = [
            'Sun' => 'Min',
            'Mon' => 'Sen',
            'Tue' => 'Sel',
            'Wed' => 'Rab',
            'Thu' => 'Kam',
            'Fri' => 'Jum',
            'Sat' => 'Sab',
        ];

        $labels = $last7Days->map(function($date) use ($hariIndo) {
            $namaHariInggris = date('D', strtotime($date)); 
            return $hariIndo[$namaHariInggris]; 
        });

        $incomingData = $last7Days->map(function($date) {
            return StockTransaction::where('type', 'Masuk')
                ->where('status', 'Diterima')
                ->whereDate('date', $date)
                ->sum('quantity');
        });

        $outgoingData = $last7Days->map(function($date) {
            return StockTransaction::where('type', 'Keluar')
                ->where('status', 'Dikeluarkan')
                ->whereDate('date', $date)
                ->sum('quantity');
        });

        return view('admin.dashboard', compact(
            'totalProducts', 'totalIncoming', 'totalOutgoing', 
            'recentActivities', 'chartData',
            'labels', 'incomingData', 'outgoingData'
        ));
    }

    /**
     * Dashboard MANAGER GUDANG
     */
    public function managerIndex()
    {
        $lowStockProducts = Product::whereColumn('current_stock', '<=', 'minimum_stock')
            ->take(5)
            ->get();
            
        $lowStockCount = Product::whereColumn('current_stock', '<=', 'minimum_stock')
            ->count();

        // Ringkasan Transaksi HARI INI yang sudah terkonfirmasi
        $incomingToday = StockTransaction::where('type', 'Masuk')
            ->where('status', 'Diterima')
            ->whereDate('date', now())
            ->sum('quantity');

        $outgoingToday = StockTransaction::where('type', 'Keluar')
            ->where('status', 'Dikeluarkan')
            ->whereDate('date', now())
            ->sum('quantity');

        $recentTransactions = StockTransaction::with([
                'user', 
                'product' => function($query) {
                    $query->withTrashed();
                }
            ])
            ->latest()
            ->take(5)
            ->get();

        return view('manager.dashboard', compact(
            'lowStockProducts',
            'lowStockCount',
            'incomingToday',
            'outgoingToday',
            'recentTransactions'
        ));
    }

    /**
     * Dashboard STAFF GUDANG 
     */
    public function staffIndex()
    {
        $totalProducts = Product::count();
        $lowStockCount = Product::whereColumn('current_stock', '<=', 'minimum_stock')->count();

        // Agar barang yang sudah dikonfirmasi otomatis hilang dari dashboard tugas
        $pendingIncoming = StockTransaction::with(['user', 'product' => function($query) {
                $query->withTrashed();
            }])
            ->where('type', 'Masuk')
            ->where('status', 'Pending') 
            ->latest()
            ->get();

        $pendingOutgoing = StockTransaction::with(['user', 'product' => function($query) {
                $query->withTrashed();
            }])
            ->where('type', 'Keluar')
            ->where('status', 'Pending')
            ->latest()
            ->get();

        return view('staff.dashboard', compact(
            'totalProducts',
            'lowStockCount',
            'pendingIncoming',
            'pendingOutgoing'
        ));
    }
}