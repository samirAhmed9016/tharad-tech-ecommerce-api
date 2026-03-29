<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Models\Category;
use App\Models\User;
use App\Observers\Category\CategoryObserver;
use App\Observers\UserObserver;

class ObserverServiceProvider extends ServiceProvider
{
    protected $listen = [];
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Category::observe(CategoryObserver::class);
        User::observe(UserObserver::class);
    }
}
