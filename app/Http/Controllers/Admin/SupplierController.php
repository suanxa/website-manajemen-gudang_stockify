<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SupplierService;
use Illuminate\Http\Request;
use App\Models\Supplier;

class SupplierController extends Controller
{
    protected $supplierService;

    public function __construct(SupplierService $supplierService) {
        $this->supplierService = $supplierService;
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $suppliers = Supplier::when($search, function ($query, $search) {
            return $query->where('name', 'LIKE', "%{$search}%")
                        ->orWhere('email', 'LIKE', "%{$search}%");
        })
        ->latest()
        ->paginate(10);

        // --- TAMBAHAN: Deteksi Role Berdasarkan Path ---
        $isManager = str_contains($request->path(), 'manager');

        return view('admin.suppliers.index', compact('suppliers', 'isManager'));
    }

    
    public function store(Request $request) {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
        ]);
        $this->supplierService->createSupplier($data);
        return back()->with('success', 'Supplier berhasil ditambah');
    }

    public function update(Request $request, $id) {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
        ]);
        $this->supplierService->updateSupplier($id, $data);
        return back()->with('success', 'Supplier berhasil diperbarui');
    }

    public function destroy($id)
    {
        $supplier = Supplier::findOrFail($id);
        $productCount = $supplier->products()->count();

        if ($productCount > 0) {
            return back()->with('error', "Supplier '{$supplier->name}' tidak bisa dihapus karena masih menyuplai $productCount produk.");
        }

        $supplier->delete();
        return back()->with('success', 'Supplier berhasil dihapus!');
    }
}