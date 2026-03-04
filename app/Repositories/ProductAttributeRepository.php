<?php

namespace App\Repositories;

use App\Models\ProductAttribute;

class ProductAttributeRepository
{
    public function createMany(array $attributes)
    {
        return ProductAttribute::insert($attributes);
    }

    public function deleteByProductId($productId)
    {
        return ProductAttribute::where('product_id', $productId)->delete();
    }
}