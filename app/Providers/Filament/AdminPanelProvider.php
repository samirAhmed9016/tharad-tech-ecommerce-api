<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Settings;
use App\Filament\Resources\CategoryResource;
use App\Filament\Resources\OrderItemResource;
use App\Filament\Resources\OrderResource;
use App\Filament\Resources\OrderResource\Widgets\statisticsOrderWidget;
use App\Filament\Resources\ProductResource;
use App\Filament\Resources\ProductResource as ResourcesProductResource;
use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Outerweb\FilamentTranslatableFields\Filament\Plugins\FilamentTranslatableFieldsPlugin;
use CraftForge\FilamentLanguageSwitcher\FilamentLanguageSwitcherPlugin;
use Filament\Navigation\NavigationBuilder;
use Filament\Navigation\NavigationGroup;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Ramsey\Collection\Set;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->brandName(fn() => setting('app_name', 'MyApp'))
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->profile()
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
            // todo user_multi_widget
            //static content trans
            //statistics
            statisticsOrderWidget::class,
            AccountWidget::class,
                // FilamentInfoWidget::class,
            ])
            ->plugins([
                FilamentTranslatableFieldsPlugin::make()
                    ->supportedLocales([
                        'ar' => 'Arabic',
                        'en' => 'English',
                    ]),

                // FilamentLanguageSwitcherPlugin::make()
                //     ->locales([
                //         ['code' => 'ar', 'name' => 'عربي', 'flag' => 'sa'],
                //         ['code' => 'en', 'name' => 'English', 'flag' => 'us'],
                //     ]),

            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->navigation(
                function (NavigationBuilder $builder): NavigationBuilder {
                    return $builder
                        ->items([
                            ...Dashboard::getNavigationItems(),
                        ])
                        ->groups([
                            NavigationGroup::make('Stock')
                                ->label(__('Stock management'))
                                ->items([
                                    ...CategoryResource::getNavigationItems(),
                                    ...ProductResource::getNavigationItems(),
                                ]),
                            NavigationGroup::make('orders')
                                ->label(__('order management'))
                                ->items([

                                    ...OrderResource::getNavigationItems(),
                                    ...OrderItemResource::getNavigationItems()
                                ]),

                            NavigationGroup::make('settings')
                                ->label(__('Settings'))
                                ->items([
                                    ...Settings::getNavigationItems(), // ← Add this
                                ]),

                            // ->items($this->fetchNavigationItems());
                        ]);
                }
            );
    }
}
