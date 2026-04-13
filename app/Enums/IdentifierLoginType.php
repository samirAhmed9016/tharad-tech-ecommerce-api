<?php

namespace App\Enums;

enum IdentifierLoginType: string
{
    case PHONE    = 'phone_number';
    case EMAIL       = 'email';

    public function title()
    {
        return match ($this) {
            self::PHONE       => __('Phone'),
            self::EMAIL          => __('Email'),
        };
    }

    public static function options()
    {
        return collect(self::cases())
            ->mapWithKeys(
                fn($item) => [ $item->value => self::from($item->value)->title() ]
            )->toArray();
    }
    public static function fromMixed(mixed $value): self
    {
        if ($value instanceof self) return $value;
        return self::from($value);
    }
}
