<?php

namespace App\Providers;

use App\Repositories\Auth\V1\AuthRepository;
use App\Repositories\Auth\V1\AuthRepositoryInterface;
use App\Repositories\Products\v1\ProductRepository;
use App\Repositories\Products\v1\ProductRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(AuthRepositoryInterface::class, AuthRepository::class);
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
