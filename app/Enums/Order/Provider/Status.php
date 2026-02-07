<?php

namespace App\Enums\Order\Provider;

enum Status : string
{
    case PENDING               = 'pending';
    case CANCELED              = 'canceled';
    case REJECTED              = 'rejected';
    case ACCEPTED             = 'accepted';

    public function title($locale = null)
    {
        return match ($this) {
            self::PENDING       => __('Pending', [], $locale),
            self::CANCELED       => __('Canceled', [], $locale),
            self::REJECTED       => __('Rejected', [], $locale),
            self::ACCEPTED       => __('Accepted', [], $locale),
        };
    }

    public static function options()
    {
        return collect(self::cases())
            ->mapWithKeys(
                fn($item) => [ $item->value => self::from($item->value)->title() ]
            )->toArray();
    }

    public function color()
    {
        return match ($this) {
            self::PENDING       => 'warning',
            self::ACCEPTED       => 'success',
            self::REJECTED       => 'danger',
            self::CANCELED       => 'danger',
        };
    }

    public static function colors()
    {
        return collect(self::cases())
            ->mapWithKeys(
                fn($item) => [ $item->value => self::from($item->value)->color() ]
            )->toArray();
    }
}
