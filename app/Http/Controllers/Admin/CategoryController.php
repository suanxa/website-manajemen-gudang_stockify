<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

   public function index(Request $request)
    {
        $search = $request->input('search');

        $categories = \App\Models\Category::when($search, function ($query, $search) {
            return $query->where('name', 'LIKE', "%{$search}%");
        })
        ->latest()
        ->paginate(10);

        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $this->categoryService->createCategory($request->all());
        return redirect()->back()->with('success', 'Kategori berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $this->categoryService->updateCategory($id, $request->all());
        return redirect()->back()->with('success', 'Kategori berhasil diperbarui');
    }

    public function destroy($id)
    {
        $category = \App\Models\Category::findOrFail($id);

        // Hitung berapa produk yang masih pakai kategori ini
        $productCount = $category->products()->count();

        if ($productCount > 0) {
            // Jika masih ada produk, kirim pesan error
            return back()->with('error', "Gagal menghapus! Masih ada $productCount produk di kategori ini. Silakan pindahkan atau hapus produknya terlebih dahulu.");
        }

        $category->delete();
        return back()->with('success', 'Kategori berhasil dihapus!');
    }
}