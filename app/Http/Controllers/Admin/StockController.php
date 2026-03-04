<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\StockService;
use App\Services\ProductService;
use App\Models\Product;
use Illuminate\Http\Request;
use Exception;

class StockController extends Controller
{
    protected $stockService;
    protected $productService;

    public function __construct(StockService $stockService, ProductService $productService)
    {
        $this->stockService = $stockService;
        $this->productService = $productService;
    }

    /**
     * Menampilkan halaman riwayat transaksi stok
     */
    public function index()
    {
        $transactions = $this->stockService->getHistoryData();
        $products = Product::orderBy('name', 'asc')->get(); 

        // DETEKSI ROLE: Untuk menentukan prefix route di Blade (admin/manager/staff)
        $role = auth()->user()->role;
        $routePrefix = $role;
        
        return view('admin.stock.index', compact('transactions', 'products', 'routePrefix'));
    }

    /**
     * Menyimpan transaksi stok baru (Status: Pending)
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'type'       => 'required|in:Masuk,Keluar',
            'quantity'   => 'required|integer|min:1',
            'date'       => 'required|date',
            'notes'      => 'nullable|string',
        ]);

        // ALUR BARU: Default status adalah 'Pending' agar Staff yang konfirmasi fisik
        $data['status'] = 'Pending';

        try {
            $this->stockService->processTransaction($data);
            return back()->with('success', 'Rencana transaksi berhasil dibuat. Menunggu konfirmasi fisik oleh Staff.');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * FUNGSI BARU: Konfirmasi Transaksi (Tugas Utama Staff)
     */
    public function confirm($id, Request $request)
    {
        $request->validate([
            'status' => 'required|in:Diterima,Dikeluarkan,Ditolak'
        ]);

        try {
            $this->stockService->confirmTransaction($id, $request->status);
            return back()->with('success', 'Transaksi berhasil dikonfirmasi.');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Halaman Stock Opname (Penyesuaian Fisik)
     */
    public function opnameIndex(Request $request) // Tambahkan parameter Request
    {
        // 1. Ambil data kategori untuk dropdown filter di View
        $categories = \App\Models\Category::orderBy('name', 'asc')->get();

        // 2. Gunakan query builder agar bisa difilter
        $query = Product::with('category');

        // 3. Filter berdasarkan pencarian Nama atau SKU
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        // 4. Filter berdasarkan Kategori
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // 5. Ambil data dengan pagination agar tidak berat jika produk sangat banyak
        // withQueryString() penting agar saat pindah page, filternya tidak hilang
        $products = $query->orderBy('name', 'asc')->paginate(10)->withQueryString();

        return view('admin.stock.opname', compact('products', 'categories'));
    }

    public function storeOpname(Request $request)
    {
        $data = $request->validate([
            'product_id'     => 'required|exists:products,id',
            'physical_stock' => 'required|integer|min:0',
            'notes'          => 'nullable|string',
        ]);

        try {
            $this->stockService->processOpname($data);
            return back()->with('success', 'Stock Opname berhasil. Stok sistem disinkronkan dengan stok fisik.');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Pengaturan Stok Minimum (Alert)
     */
    public function lowStockSettings()
    {
        $lowStockProducts = Product::whereColumn('current_stock', '<=', 'minimum_stock')
                            ->orderBy('current_stock', 'asc')
                            ->get();

        return view('admin.stock.settings', compact('lowStockProducts'));
    }
}