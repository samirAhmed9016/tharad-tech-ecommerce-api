<?php

namespace App\Enums\Provider;

enum Status : string
{
    case PENDING    = 'pending';
    case APPROVED       = 'approved';
    case REJECTED       = 'rejected';

    public function title()
    {
        return match ($this) {
            self::PENDING       => __('pending'),
            self::APPROVED          => __('approved'),
            self::REJECTED          => __('rejected'),
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
            self::APPROVED          => 'success',
            self::REJECTED          => 'danger',
        };
    }
    public static function fromMixed(mixed $value): self
    {
        if ($value instanceof self) {
            return $value;
        }
        return self::from($value);
    }

    public static function colors()
    {
        return collect(self::cases())
            ->mapWithKeys(
                fn($item) => [ $item->value => self::from($item->value)->color() ]
            )->toArray();
    }

    public static function keys() {
        return implode(',', array_keys(self::options()));
    }
}
