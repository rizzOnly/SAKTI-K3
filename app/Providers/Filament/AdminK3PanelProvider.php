<?php
namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use App\Http\Middleware\EnsureIsAdminK3;

class AdminK3PanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('admin-k3')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Blue,
                'danger'  => Color::Red,
                'warning' => Color::Amber,
                'success' => Color::Green,
            ])

            ->brandName('SAKTI K3 Admin')
            ->brandLogo(fn () => view('filament.components.brand-logo', [
                'nama' => 'Admin K3'
            ]))
            ->brandLogoHeight('3rem')
            ->favicon(asset('images/logo-sakti.png'))
            ->discoverResources(in: app_path('Filament/AdminK3/Resources'), for: 'App\\Filament\\AdminK3\\Resources')
            ->discoverPages(in: app_path('Filament/AdminK3/Pages'), for: 'App\\Filament\\AdminK3\\Pages')
            ->pages([Pages\Dashboard::class])
            ->discoverWidgets(in: app_path('Filament/AdminK3/Widgets'), for: 'App\\Filament\\AdminK3\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
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
                EnsureIsAdminK3::class,
            ])
            ->authGuard('web');
    }
}
