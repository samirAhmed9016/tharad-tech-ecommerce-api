<?php

namespace App\Enums\Slider;

enum LinkType: string
{
    case URL = 'url';
    case PRODUCT = 'product';
    case CATEGORY = 'category';
    case VENDOR = 'vendor';
    public function title()
    {
        return match ($this) {
            self::URL => __('URL'),
            self::PRODUCT => __('Product'),
            self::CATEGORY => __('Category'),
            self::VENDOR => __('Vendor'),
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
            self::URL => 'primary',
            self::PRODUCT => 'success',
            self::CATEGORY => 'warning',
            self::VENDOR => 'info',
        };
    }

    public static function keys() {
        return implode(',', array_keys(self::options()));
    }
}
