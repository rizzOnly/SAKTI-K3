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
use App\Http\Middleware\EnsureIsDokter;

class KlinikPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('klinik')
            ->path('klinik')
            ->login()
            ->colors([
                'primary' => Color::Teal,
                'danger'  => Color::Red,
                'warning' => Color::Amber,
                'success' => Color::Green,
            ])
            ->brandName('SAKTI K3 Klinik')
            ->brandLogo(fn () => view('filament.components.brand-logo', [
                'nama' => 'Klinik K3'
            ]))
            ->brandLogoHeight('3rem')
            ->favicon(asset('images/logo-sakti.png'))
            ->discoverResources(in: app_path('Filament/Klinik/Resources'), for: 'App\\Filament\\Klinik\\Resources')
            ->discoverPages(in: app_path('Filament/Klinik/Pages'), for: 'App\\Filament\\Klinik\\Pages')
            ->pages([Pages\Dashboard::class])
            ->discoverWidgets(in: app_path('Filament/Klinik/Widgets'), for: 'App\\Filament\\Klinik\\Widgets')
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
                EnsureIsDokter::class,
            ])
            ->authGuard('web');
    }
}
