<?php

namespace App\Enums;

enum Action: string
{
    case FORGET_PASSWORD    = 'forget_password';
    case VERIFY_EMAIL       = 'verify_email';
    case VERIFY_PHONE       = 'verify_phone';

    public function title()
    {
        return match ($this) {
            self::FORGET_PASSWORD       => __('Forget password'),
            self::VERIFY_EMAIL          => __('Verify email'),
            self::VERIFY_PHONE          => __('Verify phone'),
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
