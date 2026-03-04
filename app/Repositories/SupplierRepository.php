<?php

namespace App\Repositories;
use App\Models\Supplier;

class SupplierRepository {
    public function getAll() {
        return Supplier::orderBy('name', 'asc')->get();
    }

    public function findById($id) {
        return Supplier::findOrFail($id);
    }

    public function store(array $data) {
        return Supplier::create($data);
    }

    public function update($id, array $data) {
        $supplier = $this->findById($id);
        $supplier->update($data);
        return $supplier;
    }

    public function delete($id) {
        return Supplier::destroy($id);
    }
}