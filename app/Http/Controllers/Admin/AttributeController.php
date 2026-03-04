<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductAttribute as Attribute;

class AttributeController extends Controller
{
    public function index()
    {
        // Pastikan hanya ambil master (yang tidak punya produk)
        $attributes = Attribute::whereNull('product_id')->get();
        return view('admin.attributes.index', compact('attributes'));
    }

    public function store(Request $request)
    {
        // Perbaikan Validasi: Unik hanya untuk yang product_id-nya NULL
        $request->validate([
            'name' => 'required|unique:product_attributes,name,NULL,id,product_id,NULL'
        ]);

        // Paksa product_id NULL agar masuk ke kategori Master
        Attribute::create([
            'name' => $request->name,
            'product_id' => null,
            'value' => null // Master tidak butuh value
        ]);

        return back()->with('success', 'Master Atribut berhasil ditambah');
    }

    public function destroy($id)
    {
        // 1. Ambil data master atribut yang mau dihapus
        $masterAttr = Attribute::where('id', $id)->whereNull('product_id')->firstOrFail();

        // 2. Cek apakah ada data di tabel yang sama (sebagai anak) yang memakai NAMA ini
        // Kita cek ke records yang product_id-nya TIDAK NULL
        $isUsed = Attribute::where('name', $masterAttr->name)
                        ->whereNotNull('product_id')
                        ->exists();

        // 3. Jika masih ada produk yang pakai, STOP. Jangan kasih hapus!
        if ($isUsed) {
            return back()->with('error', "Atribut '{$masterAttr->name}' tidak bisa dihapus karena masih menempel pada produk. Hapus dulu atribut ini dari produk-produk terkait.");
        }

        // 4. Kalau sudah bersih, baru eksekusi hapus
        $masterAttr->delete();
        
        return back()->with('success', 'Master Atribut berhasil dihapus');
    }
}