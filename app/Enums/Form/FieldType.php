<?php

namespace App\Enums\Form;

enum FieldType: string
{
    case TEXT = 'text';
    case TEXTAREA = 'textarea';
    case RADIO = 'radio';
    case CHECKBOX = 'checkbox';
    case SELECT = 'select';
    case EMAIL = 'email';
    case NUMBER = 'number';
    case DATE = 'date';
    case TIME = 'time';
    case DATETIME = 'datetime';
    case FILE = 'file';
    case URL = 'url';
    case TEL = 'tel';

    public function title(): string
    {
        return match ($this) {
            self::TEXT => __('Text'),
            self::TEXTAREA => __('Textarea'),
            self::RADIO => __('Radio'),
            self::CHECKBOX => __('Checkbox'),
            self::SELECT => __('Select'),
            self::EMAIL => __('Email'),
            self::NUMBER => __('Number'),
            self::DATE => __('Date'),
            self::TIME => __('Time'),
            self::DATETIME => __('Date & Time'),
            self::FILE => __('File'),
            self::URL => __('URL'),
            self::TEL => __('Phone'),
        };
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(
                fn ($item) => [$item->value => self::from($item->value)->title()]
            )->toArray();
    }
}
