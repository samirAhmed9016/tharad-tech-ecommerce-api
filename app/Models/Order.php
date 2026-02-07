<?php

namespace App\Models;

use App\Enums\Order\PaymentMethod;
use App\Enums\Order\Status;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasUuids;

    protected $fillable = [
        'user_id',
        'order_number',
        'shipping_name',
        'shipping_phone',
        'shipping_address',
        'shipping_city',
        'shipping_postal_code',
        'total_price',
        'status',
        'payment_method',
        'payment_status',
        'notes',
    ];

    public function scopeStatus($query, Status $status)
    {
        return $query->where('status', $status->value);
    }

    public function scopePaymentStatus($query, PaymentMethod $status)
    {
        return $query->where('payment_status', $status->value);
    }
    public function stats(): array
    {
        return [
            Stat::make('Total Orders', $this->count()),
            Stat::make('Total Revenue', $this->sum('total_price')),
        ];
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
