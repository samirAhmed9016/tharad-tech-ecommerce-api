<?php

namespace App\Providers;

use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;
use Illuminate\Support\ServiceProvider;
use CraftForge\FilamentLanguageSwitcher\FilamentLanguageSwitcherPlugin;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->loadApiVersions();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
            $switch
                ->locales(['ar', 'en'])
                ->flags([
                    'ar' => asset('flags/ar.png'),
                    'en' => asset('flags/en.png'),
                ])->flagsOnly()->circular()
            ;
        });

        // FilamentLanguageSwitcherPlugin::make()
        //     ->locales([
        //         ['code' => 'en', 'flag' => 'us'],
        //         ['code' => 'ar', 'flag' => 'sa'],
        //     ]);
    }


    //this is used to load all apis versions from the file routes/api_versions.php
    protected function loadApiVersions(): void
    {
        $path = base_path('routes/api_versions.php');

        if (file_exists($path)) {
            require $path;
        }
    }
}
