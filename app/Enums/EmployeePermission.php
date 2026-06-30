<?php

namespace App\Enums;

enum EmployeePermission: string
{
    case PRODUCT    = 'product';
    case VENDORPRODUCT = 'vendor::product';
    case PRODUCTOFVENDOR = 'product::of::vendor';
    case ORDER      = 'order';
    case ORDERITEM  = 'order::item';
    case OFFER      = 'offer';
    case REPORT     = 'report';
    case REVIEW     = 'review';
    case BRANCH     = 'branch';
    case COUPON     = 'coupon';

    public function title()
    {
        return match ($this) {
            self::PRODUCT        => __('Product'),
            self::VENDORPRODUCT  => __('Vendor Product'),
            self::PRODUCTOFVENDOR => __('Products added by vendors'),
            self::ORDER          => __('Order'),
            self::ORDERITEM      => __('Order Item'),
            self::OFFER          => __('Offer'),
            self::REPORT         => __('Report'),
            self::REVIEW         => __('Review'),
            self::BRANCH         => __('Branch'),
            self::COUPON         => __('Coupon'),
        };
    }

    public static function options()
    {
        $cases = collect(self::cases());
        $permissions = collect(self::permissions());
        $result = [];

        // Define restricted cases and their excluded permissions
        $restrictedCases = [
            self::ORDER->value,
            self::ORDERITEM->value,
            self::REPORT->value,
            self::REVIEW->value,
            self::PRODUCT->value,
        ];

        $excludedPermissions = [
            'create',
            'restore',
            'restore_any',
            'delete',
            'delete_any',
            'update',
            'force_delete',
            'force_delete_any'
        ];

        $excludedDeletePermissions = [
            'delete',
            'delete_any',
            'force_delete',
            'force_delete_any'
        ];

        $restrictedDeleteCases = [
            self::PRODUCT->value,
            self::PRODUCTOFVENDOR->value,
            self::VENDORPRODUCT->value,
            self::BRANCH->value,
        ];

        foreach ($cases as $case) {
            foreach ($permissions as $permission) {
                if (in_array($case->value, $restrictedCases) && in_array($permission, $excludedPermissions)) {
                    continue;
                }

                if(in_array($case->value, $restrictedDeleteCases) && in_array($permission, $excludedDeletePermissions)) {
                    continue;
                }

                $key = $permission . '_' . $case->value;
                $value = __(ucfirst(str_replace('_', ' ', $permission))) . ' ' . $case->title();
                $result[$key] = $value;
            }
        }

        return $result;
    }

    public function color()
    {
        return match ($this) {
            self::PRODUCT    => 'primary',
            self::VENDORPRODUCT => 'secondary',
            self::ORDER      => 'success',
            self::ORDERITEM  => 'warning',
            self::OFFER      => 'info',
            self::REPORT     => 'dark',
            self::REVIEW     => 'danger',
            self::BRANCH     => 'primary',
            self::COUPON     => 'secondary',
        };
    }

    public static function permissions() {
        return [
            'view',
            'view_any',
            'create',
            'update',
            'delete',
            'delete_any',
            'restore',
            'restore_any',
        ];
    }

    public static function keys() {
        return implode(',', array_keys(self::options()));
    }
}
