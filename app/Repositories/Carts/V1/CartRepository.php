<?php

namespace App\Repositories\Carts\V1;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CartRepository implements CartRepositoryInterface
{
    public function get()
    {
        return Cart::where('user_id', Auth::id())
            ->with('product.media')->get();
    }

    public function add($productId, $quantity = 1)
    {

        $userId = Auth::id();

        $cartItem = Cart::where('user_id', $userId)
            ->where('product_id', $productId)->first();

        if ($cartItem) {
            $cartItem->increment('quantity', $quantity);
            $cartItem->load('product.media');
            return $cartItem;
        }

        $cart = Cart::create([
            'user_id' => $userId,
            'product_id' => $productId,
            'quantity' => $quantity,
        ]);

        $cart->load('product.media');
        return $cart;
    }

    public function delete($cartItemId)
    {
        Cart::where('user_id', Auth::id())
            ->where('id', $cartItemId)->delete();
    }

    public function empty()
    {
        Cart::where('user_id', Auth::id())->delete();
    }
}
