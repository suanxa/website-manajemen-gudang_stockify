<?php

namespace App\Repositories;

use App\Models\Category;

class CategoryRepository
{
    /**
     * Mengambil semua data kategori terbaru.
     */
    public function getAll($search = null)
    {
        $query = Category::query();

        if ($search) {
            $query->where('name', 'LIKE', "%{$search}%");
        }

        return $query->latest()->get();
    }

    /**
     * Menyimpan data kategori baru.
     */
    public function store(array $data)
    {
        return Category::create($data);
    }

    /**
     * Mencari kategori berdasarkan ID.
     */
    public function findById($id)
    {
        return Category::findOrFail($id);
    }

    public function update($id, array $data)
    {
        $category = $this->findById($id);
        $category->update($data);
        return $category;
    }

    public function delete($id)
    {
        $category = $this->findById($id);
        return $category->delete();
    }
}