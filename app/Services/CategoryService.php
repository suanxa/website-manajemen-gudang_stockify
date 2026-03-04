<?php

namespace App\Services;

use App\Repositories\CategoryRepository;

class CategoryService
{
    protected $categoryRepo;

    public function __construct(CategoryRepository $categoryRepo)
    {
        $this->categoryRepo = $categoryRepo;
    }

    /**
     * Mendapatkan daftar kategori untuk ditampilkan di view.
     */
    public function getCategoryListData($search = null)
    {
        return $this->categoryRepo->getAll($search);
    }

    /**
     * Logika untuk memproses penyimpanan kategori.
     */
    public function createCategory(array $data)
    {
        $data['name'] = strtoupper($data['name']);
        
        return $this->categoryRepo->store($data);
    }
    public function updateCategory($id, array $data)
    {
        $data['name'] = strtoupper($data['name']); 
        return $this->categoryRepo->update($id, $data);
    }

    public function deleteCategory($id)
    {
        return $this->categoryRepo->delete($id);
    }
}