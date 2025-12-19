<?php

namespace App\Http\Controllers\Api\Carts\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddToCartRequest;
use App\Http\Resources\V1\CartResource;
use App\Repositories\Carts\V1\CartRepositoryInterface;

class CartController extends Controller
{
    protected $cartRepo;

    public function __construct(CartRepositoryInterface $cartRepo)
    {
        $this->cartRepo = $cartRepo;
    }

    public function index()
    {
        $cartItems = $this->cartRepo->get();

        return CartResource::collection($cartItems)->additional([
            'status' => 'success',
        ]);
    }

    public function store(AddToCartRequest $request)
    {
        $quantity = $request->input('quantity', 1);
        $cartItem = $this->cartRepo->add($request->product_id, $quantity);

        return (new CartResource($cartItem))->additional([
            'status' => 'success',
            'message' => 'Product added to cart.',
        ]);
    }

    public function destroy($id)
    {
        $this->cartRepo->delete($id);

        return response()->json([
            'status' => 'success',
            'message' => 'Cart item removed successfully.',
        ]);
    }

    public function clear()
    {
        $this->cartRepo->empty();

        return response()->json([
            'status' => 'success',
            'message' => 'Cart cleared successfully.',
        ]);
    }
}
