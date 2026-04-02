<?php

namespace App\Enums\OrderGate;

enum OrderGateStatus: string
{
    case PENDING   = 'pending';
    case APPROVED  = 'approved';
    case REJECTED  = 'rejected';
    case CANCELLED = 'cancelled';

    public function title(): string
    {
        return match ($this) {
            self::PENDING   => __('Pending'),
            self::APPROVED  => __('Approved'),
            self::REJECTED  => __('Rejected'),
            self::CANCELLED => __('Cancelled'),
        };
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn($item) => [$item->value => $item->title()])
            ->toArray();
    }

    public static function fromMixed(mixed $value): self
    {
        if ($value instanceof self) {
            return $value;
        }
        return self::from($value);
    }

    public function color(): string
    {
        return match ($this) {
            self::PENDING   => 'warning',
            self::APPROVED  => 'success',
            self::REJECTED  => 'danger',
            self::CANCELLED => 'gray',
        };
    }
}
