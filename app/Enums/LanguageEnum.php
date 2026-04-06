<?php

namespace App\Enums;

enum LanguageEnum: string
{
    case ENGLISH = '🇬🇧 English';
    case ARABIC = '🇸🇦 Arabic';
    case FRENCH = '🇫🇷 French';
    case SPANISH = '🇪🇸 Spanish';
    case GERMAN = '🇩🇪 German';
    case ITALIAN = '🇮🇹 Italian';
    case RUSSIAN = '🇷🇺 Russian';
    case CHINESE = '🇨🇳 Chinese';
    case JAPANESE = '🇯🇵 Japanese';
    case KOREAN = '🇰🇷 Korean';
    case TURKISH = '🇹🇷 Turkish';

    public function title()
    {
        return match ($this) {
            self::ENGLISH => __('🇬🇧 English'),
            self::ARABIC => __('🇸🇦 Arabic'),
            self::FRENCH => __('🇫🇷 French'),
            self::SPANISH => __('🇪🇸 Spanish'),
            self::GERMAN => __('🇩🇪 German'),
            self::ITALIAN => __('🇮🇹 Italian'),
            self::RUSSIAN => __('🇷🇺 Russian'),
            self::CHINESE => __('🇨🇳 Chinese'),
            self::JAPANESE => __('🇯🇵 Japanese'),
            self::KOREAN => __('🇰🇷 Korean'),
            self::TURKISH => __('🇹🇷 Turkish'),
        };
    }

    public static function options()
    {
        return collect(self::cases())
            ->mapWithKeys(fn($item) => [$item->value => $item->title()])
            ->toArray();
    }
}
