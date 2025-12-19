<?php

namespace App\Repositories\Products\v1;

interface ProductRepositoryInterface
{
    public function getAllProducts(array $filters = []);
    public function getProductById(int $id);
}

