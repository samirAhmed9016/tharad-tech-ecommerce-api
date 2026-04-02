<?php

namespace App\Enums;

enum AddressType: string
{
    case MY_ADDRESS    = 'my_address';
    case OTHER_ADDRESS = 'other_address';
    case PICKUP_FROM_POS = 'pickup_from_pos';

    public function title()
    {
        return match ($this) {
            self::MY_ADDRESS            => __('My Address'),
            self::OTHER_ADDRESS            => __('Other Address'),
            self::PICKUP_FROM_POS            => __('Pickup From POS'),
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
            self::MY_ADDRESS            => 'primary',
            self::OTHER_ADDRESS            => 'warning',
            self::PICKUP_FROM_POS            => 'success',
        };
    }

    public static function keys() {
        return implode(',', array_keys(self::options()));
    }
}
