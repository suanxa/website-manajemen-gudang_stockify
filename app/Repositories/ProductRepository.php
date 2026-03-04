<?php

namespace App\Repositories;

use App\Models\Product;

class ProductRepository
{
    public function getAll($search = null)
    {
        $query = Product::with('category');
        if ($search) {
            $query->where('name', 'LIKE', "%{$search}%");
        }
        return $query->latest()->get();
    }

    public function store(array $data)
    {
        return Product::create($data);
    }

    public function findById($id)
    {
        return Product::findOrFail($id);
    }

    public function update($id, array $data)
    {
        $product = $this->findById($id);
        $product->update($data);
        return $product;
    }

    public function delete($id)
    {
        $product = $this->findById($id);
        return $product->delete();
    }
}