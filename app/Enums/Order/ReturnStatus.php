<?php

namespace App\Enums\Order;

enum ReturnStatus : string
{
    case PENDING   = 'pending';
    case ACCEPTED  = 'accepted';
    case REJECTED  = 'rejected';
    case PICKUP    = 'pickup';
    case RECEIVED  = 'received';
    case RESTOCKED = 'restocked';
    case REFUNDED  = 'refunded';
    case CANCELED  = 'canceled';

    public function title()
    {
        return match ($this) {
            self::PENDING   => __('Pending'),
            self::ACCEPTED  => __('Accepted'),
            self::REJECTED  => __('Rejected'),
            self::PICKUP    => __('Pickup'),
            self::RECEIVED  => __('Received'),
            self::RESTOCKED => __('Restocked'),
            self::REFUNDED  => __('Refunded'),
            self::CANCELED  => __('Canceled'),
        };
    }

    public static function options()
    {
        return collect(self::cases())
            ->mapWithKeys(fn($item) => [ $item->value => $item->title() ])
            ->toArray();
    }

    public function color()
    {
        return match ($this) {
            self::PENDING   => 'warning',
            self::ACCEPTED  => 'info',
            self::REJECTED  => 'danger',
            self::PICKUP    => 'secondary',
            self::RECEIVED  => 'primary',
            self::RESTOCKED => 'success',
            self::REFUNDED  => 'success',
            self::CANCELED  => 'dark',
        };
    }

    public function icon()
    {
        return match ($this) {
            self::PENDING   => 'fa fa-hourglass-half',
            self::ACCEPTED  => 'fa fa-check-circle',
            self::REJECTED  => 'fa fa-times-circle',
            self::PICKUP    => 'fa fa-truck',
            self::RECEIVED  => 'fa fa-box-open',
            self::RESTOCKED => 'fa fa-warehouse',
            self::REFUNDED  => 'fa fa-undo',
            self::CANCELED  => 'fa fa-ban',
        };
    }

    public static function keys()
    {
        return implode(',', array_keys(self::options()));
    }
}
