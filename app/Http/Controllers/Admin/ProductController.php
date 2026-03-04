<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ProductService;
use App\Services\CategoryService;
use App\Services\SupplierService;
use App\Models\ProductAttribute; 
use Illuminate\Http\Request;
use App\Imports\ProductImport;
use App\Exports\ProductExport;
use App\Models\Product;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Validation\Rule; // Tambahkan ini untuk validasi SKU

class ProductController extends Controller
{
    protected $productService;
    protected $categoryService;
    protected $supplierService;

    public function __construct(ProductService $productService, CategoryService $categoryService, SupplierService $supplierService)
    {
        $this->productService = $productService;
        $this->categoryService = $categoryService;
        $this->supplierService = $supplierService;
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $products = $this->productService->getProductListData($search);
        
        $categories = $this->categoryService->getCategoryListData();
        $suppliers = $this->supplierService->getSupplierListData();
        
        $attributes = ProductAttribute::whereNull('product_id')
                        ->orderBy('name', 'asc')
                        ->get(); 
        
        return view('admin.products.index', compact(
            'products', 
            'categories', 
            'suppliers', 
            'attributes' 
        ));
    }
    
    public function store(Request $request)
    {
        // 1. CEK APAKAH SKU SUDAH ADA DI RECYCLE BIN (Soft Deleted)
        $trashedProduct = Product::onlyTrashed()->where('sku', $request->sku)->first();

        if ($trashedProduct) {
            return redirect()->back()
                ->withInput()
                ->with('error', "SKU '{$request->sku}' sudah pernah ada di Recycle Bin. Silakan pulihkan data lama tersebut atau gunakan SKU lain.");
        }

        // 2. VALIDASI STANDAR
        $request->validate([
            'name'           => 'required|string|max:255',
            'sku'            => 'required|string|unique:products,sku',
            'category_id'    => 'required|exists:categories,id',
            'supplier_id'    => 'required|exists:suppliers,id',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price'  => 'required|numeric|min:0',
            'current_stock'  => 'required|integer|min:0',
            'minimum_stock'  => 'required|integer|min:0',
            'description'    => 'nullable|string',
            'image'          => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            // CUSTOM PESAN ERROR DI SINI
            'sku.unique' => "Gagal! SKU '{$request->sku}' sudah terdaftar sebagai produk aktif. Silakan gunakan SKU lain.",
            'sku.required' => "Waduh, SKU-nya lupa diisi Bos!",
            'image.max' => "Fotonya kegedean, maksimal 2MB ya!",
        ]);
        $this->productService->createProduct($request->all());
        return redirect()->back()->with('success', 'Produk berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'sku'            => [
                'required',
                'string',
                Rule::unique('products')->ignore($id)->whereNull('deleted_at')
            ],
            'category_id'    => 'required|exists:categories,id',
            'supplier_id'    => 'required|exists:suppliers,id',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price'  => 'required|numeric|min:0',
            'minimum_stock'  => 'required|integer|min:0',
            'description'    => 'nullable|string',
            'image'          => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $this->productService->updateProduct($id, $request->except('current_stock'));
        return redirect()->back()->with('success', 'Produk berhasil diperbarui');
    }

    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);

            if ($product->current_stock > 0) {
                \App\Models\StockTransaction::create([
                    'product_id' => $product->id,
                    'user_id'    => auth()->id(),
                    'type'       => 'Keluar',
                    'quantity'   => $product->current_stock,
                    'date'       => now(),
                    'status'     => 'Dikeluarkan',
                    'notes'      => 'Penyesuaian otomatis: Produk dihapus dari sistem.'
                ]);

                $product->current_stock = 0;
                $product->save();
            }

            $product->delete();

            return back()->with('success', 'Produk berhasil dipindahkan ke Recycle Bin. Sisa stok telah disesuaikan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus produk: ' . $e->getMessage());
        }
    }

    // ==========================================
    // FITUR RECYCLE BIN (TONG SAMPAH)
    // ==========================================

    public function trash()
    {
        // Ambil hanya data yang sudah di-soft delete
        $products = Product::onlyTrashed()->with(['category', 'supplier'])->latest()->get();
        return view('admin.products.trash', compact('products'));
    }

    public function restore($id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);
        $product->restore();

        return redirect()->route('admin.products.index')->with('success', "Produk '{$product->name}' berhasil dipulihkan!");
    }

    public function forceDelete($id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);
        
        // Opsional: Hapus file foto dari storage jika ada
        if ($product->image) {
            \Storage::disk('public')->delete($product->image);
        }

        $product->forceDelete();
        return back()->with('success', 'Produk berhasil dihapus secara permanen.');
    }

    // ==========================================
    // IMPORT & EXPORT
    // ==========================================

    public function export() 
    {
        return Excel::download(new ProductExport, 'produk-stockify.xlsx');
    }

    public function import(Request $request) 
    {
        $request->validate(['file' => 'required|mimes:xlsx,xls,csv']);
        Excel::import(new ProductImport, $request->file('file'));
        return redirect()->back()->with('success', 'Data produk berhasil diimport!');
    }

    public function importExportView()
    {
        return view('admin.products.import-export');
    }

    public function template()
    {
        return Excel::download(new \App\Exports\ProductTemplateExport, 'template-import-produk.xlsx');
    }
}