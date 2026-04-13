<?php

namespace App\Enums\Project;

enum Type: string
{
    case PRODUCT       = 'product';
    case WORK       = 'work';

    public function title()
    {
        return match ($this) {
            self::PRODUCT       => __('Ready to use Product'),
            self::WORK       => __('Our work'),
        };
    }

    public static function options()
    {
        return collect(self::cases())
            ->mapWithKeys(
                fn($item) => [ $item->value => self::from($item->value)->title() ]
            )->toArray();
    }

    public static function currentUserType()
    {
        $userType = match(request()->type) {
            self::PRODUCT->value => 'product',
            self::WORK->value => 'work',
        };
        return $userType;
    }

    public function color()
    {
        return match ($this) {
            self::PRODUCT          => 'primary',
            self::WORK          => 'warning',
        };
    }

    public static function keys() {
        return implode(',', array_keys(self::options()));
    }

    public static function appTypes() {
        return implode(',', array_only(array_keys(self::options()), [self::PRODUCT->value, self::WORK->value]));
    }
}
