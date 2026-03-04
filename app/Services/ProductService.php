<?php

namespace App\Services;

use App\Repositories\ProductRepository;
use App\Models\ProductAttribute;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Exception; 

class ProductService
{
    protected $productRepo;

    public function __construct(ProductRepository $productRepo)
    {
        $this->productRepo = $productRepo;
    }

    public function getProductListData($search = null)
    {
        return $this->productRepo->getAll($search);
    }

    /**
     * Membuat produk baru beserta atributnya
     */
    public function createProduct(array $data)
    {
        return DB::transaction(function () use ($data) {
            // 1. Handle Upload Gambar
            if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
                $data['image'] = $data['image']->store('products', 'public');
            }

            // 2. Simpan Data Produk Utama
            $product = $this->productRepo->store($data);

            // --- TAMBAHAN: CATAT TRANSAKSI STOK AWAL ---
            if ($product->current_stock > 0) {
                \App\Models\StockTransaction::create([
                    'product_id' => $product->id,
                    'user_id'    => auth()->id(), 
                    'type'       => 'Masuk',         
                    'quantity'   => $product->current_stock,
                    'date'       => now(),
                    'status'     => 'Diterima',  
                    'notes'      => 'Saldo awal (Input produk baru)',
                ]);
            }
            // -------------------------------------------

            // 3. Simpan Atribut
            $names = $data['attr_names'] ?? [];
            $values = $data['attr_values'] ?? [];
            $this->saveProductAttributes($product->id, $names, $values);

            return $product;
        });
    }

    /**
     * Memperbarui produk beserta atributnya
     */
    public function updateProduct($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $product = $this->productRepo->findById($id);

            // Handle Update Gambar
            if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
                if ($product->image) {
                    Storage::disk('public')->delete($product->image);
                }
                $data['image'] = $data['image']->store('products', 'public');
            } else {
                $data['image'] = $product->image;
            }
            if (array_key_exists('current_stock', $data)) {
                unset($data['current_stock']);
            }
            $updatedProduct = $this->productRepo->update($id, $data);

            // Panggil helper untuk update atribut (Hapus lama, Simpan baru)
            $names = $data['attr_names'] ?? [];
            $values = $data['attr_values'] ?? [];
            $this->saveProductAttributes($id, $names, $values);

            return $updatedProduct;
        });
    }

    /**
     * Menghapus produk (DENGAN PROTEKSI STOK)
     */
    public function deleteProduct($id)
    {
        $product = $this->productRepo->findById($id);

        if ($product->current_stock > 0) {
            throw new Exception("Gagal menghapus! Produk '{$product->name}' masih memiliki stok sebanyak {$product->current_stock}.");
        }

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        // Hapus atribut yang nempel di produk ini
        ProductAttribute::where('product_id', $id)->delete();

        // Menggunakan soft delete sesuai repository
        return $this->productRepo->delete($id);
    }

    /**
     * Helper Logika Simpan Atribut (SINKRON)
     */
    private function saveProductAttributes($productId, $names, $values)
    {
        // 1. Hapus semua atribut yang product_id-nya adalah produk ini
        ProductAttribute::where('product_id', $productId)->delete();

        $attributes = [];
        if (is_array($names)) {
            foreach ($names as $key => $name) {
                if (!empty($name) && !empty($values[$key])) {
                    $attributes[] = [
                        'product_id' => $productId,
                        'name'       => $name,
                        'value'      => $values[$key],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }

        // 2. Insert data baru jika ada
        if (count($attributes) > 0) {
            ProductAttribute::insert($attributes);
        }
    }
}