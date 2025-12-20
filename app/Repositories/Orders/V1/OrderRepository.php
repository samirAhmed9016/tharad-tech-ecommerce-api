<?php


namespace App\Repositories\Orders\V1;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use App\Events\OrderCreated;

class OrderRepository implements OrderRepositoryInterface
{
    // only return null or order model
    public function checkout(): ?Order
    {
        $user = auth()->user();

        DB::beginTransaction();

        try {
            $cartItems = $user->carts()->with('product')->get();
            if ($cartItems->isEmpty()) {
                DB::rollBack();
                return null;
            }

            $totalPrice = $cartItems->sum(function ($item) {
                return $item->quantity * $item->product->price;
            });

            $order = Order::create([
                'user_id' => $user->id,
                'total_price' => $totalPrice,
            ]);


            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                ]);
            }
            DB::commit();

            event(new OrderCreated($user->id, $order));
            return $order->load('items.product');

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
