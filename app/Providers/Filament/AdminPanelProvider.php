<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Dashboard;
use App\Filament\Pages\Auth\Login;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use App\Models\Setting;
use App\Http\Middleware\FilamentUserSettings;
use Illuminate\Support\HtmlString;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        $panel = $panel
            ->spa()
            ->databaseTransactions()
            ->default()
            ->id('admin')
            ->path('admin')
            ->login(Login::class)
            ->registration()
            ->renderHook(
                'panels::head.end',
                fn (): string => new HtmlString('
                    <style>
                        /* Menghilangkan Header Filter (Tulisan "Filters" dan tombol "Reset") */
                        .attendance-table-clean-filter .fi-ta-filters-header {
                            display: none !important;
                        }

                        .fi-fo-field-label-col {
                            display: none !important;
                        }
                        
                        /* Menghilangkan Background Putih & Shadow Kotak Filter */
                        .attendance-table-clean-filter .fi-ta-filters {
                            background-color: transparent !important;
                            box-shadow: none !important;
                            padding: 0 !important;
                            margin-bottom: 1rem !important;
                        }
                        
                        /* Merapikan Grid agar tidak ada gap aneh */
                        .attendance-table-clean-filter .fi-ta-filters-grid {
                            background-color: transparent !important;
                        }
                    </style>
                ')
            )
            ->colors([
                'primary' => Color::Blue,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([])
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
                FilamentUserSettings::class,
            ])
            ->plugins([
                FilamentShieldPlugin::make(),
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->passwordReset()
            ->emailVerification()
            ->profile()
            ->viteTheme('resources/css/filament/admin/theme.css');

        return $panel;
    }
}