<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return [
        //     'id' => $this->id,
        //     'total_price'=> $this->total_price,
        //     'items' => $this->items->map(function ($item) {
        //         return [
        //             'id' => $item->id,
        //             'product_id' => $item->product_id,
        //             'product' => new ProductResource($item->product),
        //             'quantity' => $item->quantity,
        //             'price' => $item->price,
        //         ];
        //     }),
        //     'created_at' => $this->created_at->toDateTimeString(),
        // ];

        //here we get the data only we need
        return [
            'id' => $this->id,
            'total_price' => $this->total_price,
            'items' => $this->items->map(function ($item) {
                return [
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->getTranslation('title', app()->getLocale()),
                    'Product_image'=>$item->product->getFirstMediaUrl('products'),
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                ];
            }),
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
