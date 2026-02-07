<?php

namespace App\Enums\Order;

enum ReturnReason : string
{
    case DEFECTIVE  = 'defective';
    case WRONG_ITEM = 'wrong_item';
    case NOT_NEEDED = 'not_needed';
    case OTHER      = 'other';

    public function title()
    {
        return match ($this) {
            self::DEFECTIVE  => __('Defective item'),
            self::WRONG_ITEM => __('Wrong item delivered'),
            self::NOT_NEEDED => __('No longer needed'),
            self::OTHER      => __('Other'),
        };
    }

    public static function options()
    {
        return collect(self::cases())
            ->mapWithKeys(fn($item) => [ $item->value => $item->title() ])
            ->toArray();
    }

    public function color()
    {
        return match ($this) {
            self::DEFECTIVE  => 'danger',
            self::WRONG_ITEM => 'warning',
            self::NOT_NEEDED => 'info',
            self::OTHER      => 'secondary',
        };
    }

    public function icon()
    {
        return match ($this) {
            self::DEFECTIVE  => 'fa fa-exclamation-triangle',
            self::WRONG_ITEM => 'fa fa-exchange-alt',
            self::NOT_NEEDED => 'fa fa-minus-circle',
            self::OTHER      => 'fa fa-ellipsis-h',
        };
    }

    public static function keys()
    {
        return implode(',', array_keys(self::options()));
    }
}
