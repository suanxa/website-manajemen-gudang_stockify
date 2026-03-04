<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\StockTransaction;
use App\Models\Supplier; 
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StockReportExport;
use App\Exports\TransactionReportExport; 

class ReportController extends Controller
{
    /**
     * Laporan Stok Barang (Real-time)
     */
    public function stockReport(Request $request)
    {
        $query = Product::with(['category', 'supplier']);

        // 1. Filter Kategori
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // 2. Filter Supplier
        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        // 3. TAMBAHAN: Filter Nama Produk atau SKU
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        // Urutkan berdasarkan stok tersedikit (kritis) agar yang perlu restock muncul di atas
        $products = $query->orderBy('current_stock', 'asc')->paginate(10);
        
        $categories = Category::all();
        $suppliers = Supplier::all();
        $isManager = str_contains($request->path(), 'manager');

        return view('admin.reports.stock', compact('products', 'categories', 'suppliers', 'isManager'));
    }

    /**
     * Export Excel Laporan Stok
     */
    public function exportStock(Request $request)
    {
        // Ambil filter dari request
        $categoryId = $request->category_id;
        $search = $request->search; // Tambahkan ini agar filter search juga ikut ke Excel

        $fileName = 'laporan-stok-' . now()->format('Y-m-d_His') . '.xlsx';
        
        // Kirim search dan categoryId ke constructor Export
        return Excel::download(new StockReportExport($categoryId, $search), $fileName);
    }

    /**
     * Laporan Transaksi Barang Masuk & Keluar
     */
    public function transactionReport(Request $request)
    {
        // Load relasi lengkap: user, product, dan supplier di dalam product
        $query = StockTransaction::with(['user', 'product' => function($q) {
            $q->withTrashed()->with('supplier'); 
        }]);

        // Filter Tanggal
        if ($request->filled('start_date')) {
            $query->whereDate('date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('date', '<=', $request->end_date);
        }
        
        // Filter Tipe (Masuk/Keluar)
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // FILTER BERDASARKAN SUPPLIER (Penting!)
        if ($request->filled('supplier_id')) {
            $query->whereHas('product', function($q) use ($request) {
                $q->where('supplier_id', $request->supplier_id);
            });
        }

        $transactions = $query->latest()->paginate(15);
        $suppliers = Supplier::all(); // Data untuk dropdown filter
        $isManager = str_contains($request->path(), 'manager');

        return view('admin.reports.transactions', compact('transactions', 'isManager', 'suppliers'));
    }

    /**
     * Export Excel Laporan Transaksi
     */
    public function exportTransactions(Request $request)
    {
        $filters = [
            'start_date'  => $request->start_date,
            'end_date'    => $request->end_date,
            'type'        => $request->type,
            'supplier_id' => $request->supplier_id 
        ];

        $fileName = 'laporan-transaksi-' . now()->format('Y-m-d_His') . '.xlsx';
        
        return Excel::download(new TransactionReportExport($filters), $fileName);
    }

    /**
     * Laporan Aktivitas Pengguna (HANYA YANG SUDAH TERKONFIRMASI)
     */
    public function activityReport(Request $request)
    {
        $users = User::all(); 

        $query = StockTransaction::with(['user', 'product' => function($q) {
            $q->withTrashed();
        }])
        // KUNCI GACOR: Filter hanya status yang sudah fix merubah stok
        ->whereIn('status', ['Diterima', 'Dikeluarkan']); 

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('search_product')) {
            $search = $request->search_product;
            $query->whereHas('product', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $activities = $query->latest()->paginate(10); 
        $isManager = str_contains($request->path(), 'manager');

        return view('admin.reports.activities', compact('activities', 'isManager', 'users'));
    }

    /**
     * Galeri Visual Produk & Grafik
     */
    public function productGallery(Request $request)
    {
        // Load kategori dan supplier agar badge di galeri tidak error
        $products = Product::with(['category', 'supplier'])->get();
        
        // Data untuk grafik bar per kategori
        $categories = Category::withCount('products')->get();
        
        $isManager = str_contains($request->path(), 'manager');

        return view('admin.reports.product_gallery', compact('products', 'categories', 'isManager'));
    }
}