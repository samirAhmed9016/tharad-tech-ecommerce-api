<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',

    ];

    public function getAppNameAttribute($value)
    {
        return json_decode(Setting::where('key', 'app_name')->first()?->value, true, 512, JSON_UNESCAPED_UNICODE)->toArray()[app()->getLocale()];
    }
}
