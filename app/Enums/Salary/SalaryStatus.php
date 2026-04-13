<?php

namespace App\Enums\Salary;

enum SalaryStatus: string
{
    case PENDING = 'pending';
    case PAID    = 'paid';

    public function title(): string
    {
        return match ($this) {
            self::PENDING => __('Pending'),
            self::PAID    => __('Paid'),
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::PENDING => 'warning',
            self::PAID    => 'success',
        };
    }
    public static function fromMixed(mixed $value): self
    {
        if ($value instanceof self) return $value;
        return self::from($value);
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn($case) => [$case->value => $case->title()])
            ->toArray();
    }
}
