<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\BelongsTo;

class OrderItem extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\OrderItem>
     */
    public static $model = \App\Models\OrderItem::class;

    public static $title = 'id';

    public static $search = [
        'id',
        'order_id',
        'product_id',
    ];

    public static function authorizable(): bool
    {
        return false;
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make('ID', 'id'),

            BelongsTo::make('Order', 'order', Order::class),

            BelongsTo::make('Product', 'product', Product::class),

            Number::make('Quantity', 'quantity')
                ->sortable()
                ->rules('required', 'integer', 'min:1'),

            Currency::make('Price', 'price')
                ->sortable()
                ->rules('required', 'numeric', 'min:0'),

            Currency::make('Subtotal', 'subtotal')
                ->sortable()
                ->exceptOnForms(), // calculated automatically — no need to fill manually
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [];
    }
}
