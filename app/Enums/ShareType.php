<?php

namespace App\Enums;

enum ShareType: string
{
    case REVIEWS        = 'reviews';
    case FAVS           = 'favs';
    case ORDERS         = 'orders';

    public function title()
    {
        return match ($this) {
            self::REVIEWS       => __('Reviews'),
            self::FAVS          => __('Favs'),
            self::ORDERS        => __('Orders'),
        };
    }

    public static function options()
    {
        return collect(self::cases())
            ->mapWithKeys(
                fn($item) => [ $item->value => self::from($item->value)->title() ]
            )->toArray();
    }
}
