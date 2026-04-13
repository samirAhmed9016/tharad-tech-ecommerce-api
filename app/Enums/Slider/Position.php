<?php

namespace App\Enums\Slider;

enum Position: string
{
    case INTRO  = 'intro';
    case PRODUCT = 'product';
    case CATEGORY = 'category';
    case VENDOR = 'vendor';
    case HOME = 'home';
    case AD = 'ad';

    public function title()
    {
        return match ($this) {
            self::INTRO => __('Intro'),
            self::PRODUCT => __('Product'),
            self::CATEGORY => __('Category'),
            self::VENDOR => __('Vendor'),
            self::HOME => __('Home'),
            self::AD => __('Ad'),
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
            self::INTRO => 'danger',
            self::PRODUCT => 'success',
            self::CATEGORY => 'warning',
            self::VENDOR => 'info',
            self::HOME => 'primary',
            self::AD => 'primary',
        };
    }

    public static function keys() {
        return implode(',', array_keys(self::options()));
    }
}
