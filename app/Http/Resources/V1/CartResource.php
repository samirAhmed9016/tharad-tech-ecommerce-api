<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $product = $this->product;
        $subtotal = $this->quantity * $product->price;

        return [

            'id' => $this->id,
            'product_id' => $this->product_id,
            'product' => [
                'id' => $product->id,
                'title' => $product->getTranslation('title', app()->getLocale()),
                'description' => $product->getTranslation('description', app()->getLocale()),
                'price' => $product->price,
                'quantity' => $product->quantity,
                'image' => $product->getFirstMediaUrl('products'),
            ],
            'quantity' => $this->quantity,
            'subtotal' => $subtotal,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
