<?php

namespace App\Enums;

enum RegisterationStep: int
{
    case COMPLETED    = 1;
    case INCOMPLETED  = 0;

    public function title()
    {
        return match ($this) {
            self::COMPLETED          => __('Completed'),
            self::INCOMPLETED        => __('Incompleted'),
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
            self::INCOMPLETED          => 'danger',
            self::COMPLETED            => 'success',
        };
    }

    public static function keys() {
        return implode(',', array_keys(self::options()));
    }
}
