<?php

namespace App\Enums\Order;

enum PaymentMethod : string
{
    case CASH    = 'cash';
    case ONLINE       = 'online';
    case WALLET       = 'wallet';
    case ONLINE_WALLET       = 'online_wallet';

    public function title()
    {
        return match ($this) {
            self::CASH       => __('cash'),
            self::ONLINE          => __('online'),
            self::WALLET          => __('wallet'),
            self::ONLINE_WALLET          => __('online_wallet'),
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
            self::CASH       => 'warning',
            self::ONLINE          => 'info',
            self::WALLET          => 'success',
            self::ONLINE_WALLET          => 'success',
        };
    }

    public static function random(): string
    {
        return collect(self::cases())->random()->value;
    }

    public static function keys() {
        return implode(',', array_keys(self::options()));
    }
}
