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
use Filament\Navigation\MenuItem;
use App\Filament\AdminK3\Widgets\CustomWelcomeWidget;

class AdminK3PanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('admin-k3')
            ->path('admin')

            ->userMenuItems([
                'logout' => MenuItem::make()
                    ->label('Sign Out')
                    ->url('/logout')
                    ->icon('heroicon-o-arrow-right-on-rectangle'),
            ])

            // ── UBAH INI: matikan login bawaan Filament ──
            ->login(false)
            ->authGuard('web')

            ->colors([
                'primary' => Color::Blue,
                'danger'  => Color::Red,
                'warning' => Color::Amber,
                'success' => Color::Green,
            ])
            ->brandName('D-SAVE Admin')
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
                CustomWelcomeWidget::class,
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
            // Middleware: wajib login + punya role admin_k3
            ->authMiddleware([
                Authenticate::class,
                EnsureIsAdminK3::class,
            ]);
    }
}
