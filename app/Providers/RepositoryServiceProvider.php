<?php

namespace App\Providers;

use App\Repositories\Auth\V1\AuthRepository;
use App\Repositories\Auth\V1\AuthRepositoryInterface;
use App\Repositories\Carts\V1\CartRepository;
use App\Repositories\Carts\V1\CartRepositoryInterface;
use App\Repositories\Orders\V1\OrderRepository;
use App\Repositories\Orders\V1\OrderRepositoryInterface;
use App\Repositories\Products\v1\ProductRepository;
use App\Repositories\Products\v1\ProductRepositoryInterface;
use App\Repositories\Setting\SettingRepository;
use App\Repositories\Setting\SettingRepositoryInterface;
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
        $this->app->bind(CartRepositoryInterface::class, CartRepository::class);
        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
        $this->app->bind(SettingRepositoryInterface::class, SettingRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
