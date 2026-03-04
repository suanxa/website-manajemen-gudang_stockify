<?php

namespace App\Services;
use App\Repositories\SupplierRepository;

class SupplierService {
    protected $supplierRepo;
    public function __construct(SupplierRepository $supplierRepo) {
        $this->supplierRepo = $supplierRepo;
    }
    public function getSupplierListData() {
        return $this->supplierRepo->getAll();
    }

    public function createSupplier(array $data) {
        return $this->supplierRepo->store($data);
    }

    public function updateSupplier($id, array $data) {
        return $this->supplierRepo->update($id, $data);
    }

    public function deleteSupplier($id) {
        return $this->supplierRepo->delete($id);
    }
}