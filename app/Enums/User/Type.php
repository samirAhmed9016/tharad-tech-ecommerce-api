<?php

namespace App\Enums\User;

enum Type: string
{
    case CLIENT       = 'client';
    case DRIVER       = 'driver';
    case VENDOR       = 'vendor';
    case SELLER       = 'seller';
    case ADMIN        = 'admin';
    case SUPER_ADMIN   = 'super_admin';

    public function title()
    {
        return match ($this) {
            self::CLIENT       => __('Client'),
            self::DRIVER          => __('Driver'),
            self::VENDOR       => __('Vendor'),
            self::SELLER       => __('Seller'),
            self::ADMIN       => __('Admin'),
            self::SUPER_ADMIN => __('Super Admin'),
        };
    }

    public static function options()
    {
        return collect(self::cases())
            ->mapWithKeys(
                fn($item) => [$item->value => self::from($item->value)->title()]
            )->toArray();
    }

    public static function currentUserType()
    {
        $userType = match (request()->type) {
            self::CLIENT->value => 'client',
            self::DRIVER->value => 'driver',
            self::VENDOR->value => 'vendor',
            self::SELLER->value => 'seller',
            self::ADMIN->value  => 'admin',
            self::SUPER_ADMIN->value => 'super_admin',
        };
        return $userType;
    }

    public function color()
    {
        return match ($this) {
            self::CLIENT          => 'primary',
            self::DRIVER          => 'info',
            self::VENDOR          => 'warning',
            self::SELLER          => 'success',
            self::ADMIN          => 'danger',
            self::SUPER_ADMIN => 'danger',
        };
    }

    public static function keys()
    {
        return implode(',', array_keys(self::options()));
    }

    public static function appTypes()
    {
        return implode(',', array_only(array_keys(self::options()), [self::CLIENT->value, self::DRIVER->value]));
    }
}
