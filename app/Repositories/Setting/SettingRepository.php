<?php

namespace App\Repositories\Setting;

use App\Models\Setting;
use App\Repositories\Setting\SettingRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class SettingRepository implements SettingRepositoryInterface
{

    public function generalSettingData()
    {
        return [
            'app_name' => setting('app_name'),
            'contact_email' => setting('contact_email'),
            'contact_number' => setting('contact_number'),
            'contact_location' => setting('contact_location'),
            'logo' => Storage::disk('public')->url(setting('logo')),
            'favicon' => Storage::disk('public')->url(setting('favicon')),
            'whatsapp_number' => setting('whatsapp_number'),
            'facebook_link' => setting('facebook_link'),
            'instagram_link' => setting('instagram_link'),
            'terms_conditions' => setting('terms_conditions'),
            'policy' => setting('policy'),
        ];
    }

    public function find($key)
    {
        return Setting::where('key', $key)->first();
    }
}
