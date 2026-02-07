<?php

namespace App\Enums\Order;

enum Status : string
{
    case PENDING               = 'pending';
    // case REJECTED              = 'rejected';
    // case COMPLETED             = 'completed';
    case CANCELED              = 'canceled';
    case PAID                  = 'paid';
    case PAYMENT_FAILURE       = 'payment_failure';
    case EXPIRED               = 'expired';
    case CLIENT_RECEIVED       = 'client_received';
    case DRIVER_RECEIVED       = 'driver_received';
    case DELIVERED             = 'delivered';

    public function title($locale = null)
    {
        return match ($this) {
            self::PENDING       => __('Pending', [], $locale),
            self::CANCELED       => __('Canceled', [], $locale),
            self::PAID       => __('Paid', [], $locale),
            self::PAYMENT_FAILURE       => __('Failed payment', [], $locale),
            self::EXPIRED       => __('Expired', [], $locale),
            self::CLIENT_RECEIVED       => __('Client Received', [], $locale),
            self::DRIVER_RECEIVED       => __('Driver Received', [], $locale),
            self::DELIVERED       => __('Delivered', [], $locale),
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
            self::CANCELED       => 'danger',
            self::PAID       => 'success',
            self::PAYMENT_FAILURE       => 'danger',
            self::EXPIRED       => 'danger',
            self::CLIENT_RECEIVED       => 'success',
            self::DRIVER_RECEIVED       => 'success',
            self::DELIVERED       => 'info',

        };
    }

    // colors
    public static function colors()
    {
        return collect(self::cases())
            ->mapWithKeys(
                fn($item) => [ $item->value => self::from($item->value)->color() ]
            )->toArray();
    }

    public function icon()
    {
        return match ($this) {
            self::PENDING       => 'fa fa-clock',
            self::INPROGRESS          => 'fa fa-spinner',
            self::REJECTED          => 'fa fa-times',
            self::CANCELED          => 'fa fa-times',
            self::COMPLETED          => 'fa fa-check',
            self::INCOMPLETED          => 'fa fa-times',
            self::PAID          => 'fa fa-check',
        };
    }

    public function isCompleted()
    {
        return $this === self::COMPLETED;
    }

    public function isCanceled()
    {
        return $this === self::CANCELED;
    }

    public function isRejected()
    {
        return $this === self::REJECTED;
    }

    public function isPending()
    {
        return $this === self::PENDING;
    }

    public function isInprogress()
    {
        return $this === self::INPROGRESS;
    }

    public function isPaid()
    {
        return $this === self::PAID;
    }

    public function isReadyToPay()
    {
        return $this === self::COMPLETED;
    }

    public function isNotCompleted()
    {
        return !$this->isCompleted();
    }

    public function isReorderable()
    {
        return $this === self::REJECTED || $this === self::CANCELED || $this === self::PAID;
    }

    // get status according to current or finsihed
    public static function mapStatus($status)
    {
        return $status == 'current' ? [
            self::PENDING->value,
            self::INPROGRESS->value,
            self::COMPLETED->value,

        ] : [
            self::REJECTED,
            self::CANCELED->value,
            self::PAID->value,
        ];

    }

    public static function random(): string
    {
        return collect(self::cases())->random()->value;
    }

    public static function keys() {
        return implode(',', array_keys(self::options()));
    }
}
