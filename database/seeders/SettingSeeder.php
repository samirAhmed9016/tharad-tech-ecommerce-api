<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // Basic App Info
            [
                'key' => 'app_name',
                'value' => json_encode([
                    'ar' => 'تطبيقي',
                    'en' => 'MyApp'
                ], JSON_UNESCAPED_UNICODE)
            ],

            // Contact Information
            ['key' => 'contact_email', 'value' => 'support@myapp.com'],
            ['key' => 'contact_number', 'value' => '966512345678'],
            [
                'key' => 'contact_location',
                'value' => json_encode([
                    'ar' => 'الرياض، السعودية',
                    'en' => 'Riyadh, Saudi Arabia'
                ], JSON_UNESCAPED_UNICODE)
            ],

            // Images
            ['key' => 'logo', 'value' => 'assets/images/logo.png'],
            ['key' => 'favicon', 'value' => 'assets/images/favicon.png'],

            // Social Media
            ['key' => 'whatsapp_number', 'value' => '966512345678'],
            ['key' => 'facebook_link', 'value' => 'https://facebook.com/myapp'],
            ['key' => 'instagram_link', 'value' => 'https://instagram.com/myapp'],

            // Terms & Policy (translatable)
            [
                'key' => 'terms_conditions',
                'value' => json_encode([
                    'ar' => 'الشروط والأحكام هنا...',
                    'en' => 'Terms and conditions here...'
                ], JSON_UNESCAPED_UNICODE)
            ],
            [
                'key' => 'policy',
                'value' => json_encode([
                    'ar' => 'سياسة الخصوصية هنا...',
                    'en' => 'Privacy policy here...'
                ], JSON_UNESCAPED_UNICODE)
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                ['value' => $setting['value']]
            );
        }
    }
}
