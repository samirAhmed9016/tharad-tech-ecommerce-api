<?php

namespace App\Repositories\Products\v1;

use App\Models\Product;
use Illuminate\Support\Facades\Cache;

class ProductRepository implements ProductRepositoryInterface
{

    // public function getAllProducts()
    // {
    //     return Product::orderBy('id', 'desc')->paginate();
    // }
    public function getAllProducts(array $filters = [])
    {
        $params = array_filter($filters); // php function to remove null values
        //so we can work on the params that passed only
        ksort($params); // sort params
        $cacheKey = 'products_' . md5(json_encode($params));


        return Cache::remember($cacheKey, 600, function () use ($filters) {
            $query = Product::query();

            // Price filter
            if (isset($filters['min_price']) && isset($filters['max_price'])) {
                $query->whereBetween('price', [$filters['min_price'], $filters['max_price']]);
            } elseif (isset($filters['min_price'])) {
                $query->where('price', '>=', $filters['min_price']);
            } elseif (isset($filters['max_price'])) {
                $query->where('price', '<=', $filters['max_price']);
            }

            //here the filters that user can apply to the data
            $sortOptions = [
                'price_asc' => ['price', 'asc'],
                'price_desc' => ['price', 'desc'],
                'created_at_asc' => ['created_at', 'asc'],
                'created_at_desc' => ['created_at', 'desc'],
            ];
            $sortBy = $filters['sort_by'] ?? 'created_at_desc';
            $sort = $sortOptions[$sortBy] ?? ['created_at', 'desc'];
            $query->orderBy($sort[0], $sort[1]);
            return $query->paginate();
        });
    }

    public function getProductById(int $id)
    {
        return Product::find($id);
    }
}
