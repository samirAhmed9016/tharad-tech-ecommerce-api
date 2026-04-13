<?php

namespace App\Enums\Salary;

enum SalaryItemType: string
{
    case ADDITION  = 'addition';
    case DEDUCTION = 'deduction';

    public function title(): string
    {
        return match ($this) {
            self::ADDITION  => __('Addition'),
            self::DEDUCTION => __('Deduction'),
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::ADDITION  => 'success',
            self::DEDUCTION => 'danger',
        };
    }

    public static function fromMixed(mixed $value): self
    {
        if ($value instanceof self) return $value;
        return self::from($value);
    }

    public function isAddition(): bool
    {
        return $this === self::ADDITION;
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn($case) => [$case->value => $case->title()])
            ->toArray();
    }
}
