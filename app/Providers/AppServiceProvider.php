<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        //
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
