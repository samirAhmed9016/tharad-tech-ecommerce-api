<?php

namespace App\Http\Controllers\Api\Products\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\ProductResource;
use App\Repositories\Products\v1\ProductRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class ProductController extends Controller
{
    protected $productRepo;

    public function __construct(ProductRepositoryInterface $productRepo)
    {
        $this->productRepo = $productRepo;
    }


    public function index()
    {
        try {
            // Get filters from request
            $filters = request()->only(['min_price', 'max_price', 'sort_by']);
            $products = $this->productRepo->getAllProducts($filters);


            return ProductResource::collection($products)->additional([
                'status' => 'success',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch products.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function show(int $id)
    {
        $product = $this->productRepo->getProductById($id);

        if (!$product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found',
            ], 404);
        }

        return (new ProductResource($product))->additional([
            'status' => 'success',
        ]);
    }
}
