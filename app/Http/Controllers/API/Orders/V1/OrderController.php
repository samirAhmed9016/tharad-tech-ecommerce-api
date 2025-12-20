<?php

namespace App\Http\Controllers\Api\Orders\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\OrderResource;
use App\Repositories\Orders\V1\OrderRepositoryInterface;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $orderRepo;

    public function __construct(OrderRepositoryInterface $orderRepo)
    {
        $this->orderRepo = $orderRepo;
    }

    public function checkout()
    {
        try {
            $order = $this->orderRepo->checkout();

            if (!$order) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'something wrong or cart is empty',
                ], 400);
            }

            return (new OrderResource($order))->additional([
                'status' => 'success',
            ])->response();
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Checkout failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
