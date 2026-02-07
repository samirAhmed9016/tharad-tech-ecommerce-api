
<?php

use Illuminate\Support\Facades\{Schema, DB, Log};


if (!function_exists('setting')) {
    function setting($key, $default = null)
    {
        // check if settings table exists
        if (!Schema::hasTable('settings')) {
            return;
        }

        $value = DB::table('settings')->where('key', $key)->first()?->value;
        if (translatableSetting($key)) {
            try {
                return json_decode($value, true)[app()->getLocale()];
            } catch (\Exception $e) {
                Log::error("Error decoding JSON for setting key: {$key}. Error: {$e->getMessage()}");
            }
        }
        if (is_array(json_decode($value, true))) {
            return json_decode($value, true)[app()->getLocale()];
        }

        if ($key == 'app_profit' || $key == 'vat' || $key == 'provider_profit') {
            return (float)$key / 100;
        }
        return $value ?? $default;
    }
}

if (!function_exists('translatableSetting')) {
    function translatableSetting($key)
    {
        $translatableSettings = [
            'app_name',
            'about_desc',
            'client_terms',
            'client_policy',
            'provider_terms',
            'provider_policy',
            'contact_location',
            'commercial_name',
            'about_desc',
            'driver_terms',
            'driver_policy',
            'lcation',
            'footer_about',
            'terms_conditions',
            'seeker_terms',
            'seeker_policy',
            'employer_terms',
            'employer_policy',
            'premium_body',
            'premium_accounts_count',
            'current_applicants_count',
            'current_employers_count',
            'hired_seekers_count'
        ];
        return in_array($key, $translatableSettings);
    }
}
