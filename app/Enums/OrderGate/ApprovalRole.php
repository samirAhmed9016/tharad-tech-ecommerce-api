<?php

namespace App\Enums\OrderGate;

enum ApprovalRole: string
{
    case HR    = 'hr';
    case ADMIN = 'admin';

    public function title(): string
    {
        return match ($this) {
            self::HR    => __('HR'),
            self::ADMIN => __('Admin'),
        };
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn($item) => [$item->value => $item->title()])
            ->toArray();
    }
}
