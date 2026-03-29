<?php

namespace App\Providers;

// use App\Models\Category;

use App\Nova\Category;
use App\Nova\Dashboards\Main;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Menu\MenuItem;
use Laravel\Nova\Menu\MenuSection;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
        $this->getCustomMenu();
        $this->getFooterContent();
    }

    /**
     * Register the Nova routes.
     *
     * @return void
     */
    protected function routes()
    {
        Nova::routes()
                ->withAuthenticationRoutes(default: true)
                ->withPasswordResetRoutes()
                ->register();
    }

    /**
     * Register the Nova gate.
     *
     * This gate determines who can access Nova in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewNova', function ($user) {
            return in_array($user->email, [
                //
            ]);
        });
    }

    /**
     * Get the dashboards that should be listed in the Nova sidebar.
     *
     * @return array
     */
    protected function dashboards()
    {
        return [
            new \App\Nova\Dashboards\Main,
        ];
    }

    /**
     * Get the tools that should be listed in the Nova sidebar.
     *
     * @return array
     */
    public function tools()
    {
        return [];
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
    private function getFooterContent()
    {
        Nova::footer(function ($request) {
            $view = 'nova/footer';

            return view()->exists($view)
                ? Blade::render(view($view)->render())
                : '<p class="text-center text-sm text-primary-500">&copy; ' . date('Y') . ' All rights reserved.</p>';
        });
    }


    private function getCustomMenu()
    {
        Nova::mainMenu(function ($request) {
            return [
                MenuSection::dashboard(Main::class)
                    ->icon('home'),

                MenuSection::make('Users', [
                    MenuItem::make('All Users', '/resources/users'),
                    MenuItem::make('Create User', '/resources/users/new')
                        ->canSee(function (NovaRequest $request) {
                            return $request->user()->is_admin;
                        })
                ])->icon('users')->collapsable(),



                MenuSection::resource(Category::class)
                    ->icon('category'),

                MenuSection::make('Products', [
                    MenuItem::make('All Products', '/resources/products'),
                    MenuItem::make('Create Product', '/resources/products/new'),
                ])->icon('shopping-bag')->collapsable(),

                MenuSection::make('Orders', [
                    MenuItem::make('All Orders', '/resources/orders')
                ])->icon('cart')->collapsable(),

                MenuSection::make('Order Items', [
                    MenuItem::make('All Order Items', '/resources/order-items'),
                ])->icon('shopping-cart')->collapsable(),
            ];
        });
    }
}
