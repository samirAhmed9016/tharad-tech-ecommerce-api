<?php

namespace App\Nova;

use App\Models\Order as OrderModel;
use App\Nova\OrderItem;
use App\Nova\Filters\OrderStatus;
use App\Nova\Metrics\AvgOrders;
use App\Nova\Metrics\OrderPerUser;
use App\Nova\Metrics\TotalRevenue;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Http\Requests\NovaRequest;

class Order extends Resource
{
    public static $model = OrderModel::class;
    public static $title = 'order_number';
    public static $search = ['order_number', 'shipping_name', 'shipping_phone'];

    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),

            Text::make('Order Number')->sortable(),

            BelongsTo::make('User'),

            Text::make('Shipping Name')->sortable(),
            Text::make('Shipping Phone')->sortable(),
            Text::make('Shipping Address')->hideFromIndex(),
            Text::make('Shipping City'),
            Text::make('Shipping Postal Code')->hideFromIndex(),

            Number::make('Total Price')->sortable(),

            Select::make('Status')->options([
                'pending' => 'Pending',
                'processing' => 'Processing',
                'completed' => 'Completed',
                'cancelled' => 'Cancelled',
            ])->displayUsingLabels(),

            Select::make('Payment Method')->options([
                'cash' => 'Cash',
                'card' => 'Card',
                'online' => 'Online',
            ])->displayUsingLabels(),

            Select::make('Payment Status')->options([
                'pending' => 'Pending',
                'paid' => 'Paid',
                'failed' => 'Failed',
            ])->displayUsingLabels(),

            HasMany::make('Items', 'items', OrderItem::class),
        ];
    }

    public function filters(NovaRequest $request)
    {
        return [
            new OrderStatus(),
        ];
    }

    public function cards(NovaRequest $request)
    {
        return [
            new AvgOrders(),
            new TotalRevenue(),
            new OrderPerUser(),
        ];
    }

    public function lenses(NovaRequest $request)
    {
        return [];
    }

    public function actions(NovaRequest $request)
    {
        return [];
    }

    public static function authorizable(): bool
    {
        return false;
    }
}
