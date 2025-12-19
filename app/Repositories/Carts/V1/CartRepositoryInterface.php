<?php

namespace App\Repositories\Carts\V1;

interface CartRepositoryInterface
{
    public function get();
    public function add($productId, $quantity = 1);
    public function delete($cartItemId);
    public function empty();
}
