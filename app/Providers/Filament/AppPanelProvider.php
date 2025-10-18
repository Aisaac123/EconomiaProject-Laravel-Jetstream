<?php

namespace App\Providers\Filament;

use Filament\Actions\Action;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Jetstream\JetstreamPlugin;
use Filament\Jetstream\Pages\Dashboard;
use Filament\Navigation\NavigationItem;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AppPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $this->custom(
            $panel
                ->default()
                ->id('app')
                ->viteTheme('resources/css/filament/app/theme.css')
                ->path('/')
                ->userMenuItems([
                    'profile' => fn (Action $action): Action => $action->label(\Auth::user()->name),
                ])
                ->homeUrl('/dashboard')
                ->colors(['primary' => Color::Teal[950]])
                ->brandLogo(asset('assets/app-icon.png'))
                ->favicon('assets/app-icon.png')
                ->brandLogoHeight('40px')
                ->login()
                ->registration()
                ->passwordReset()
                ->emailVerification()
                ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
                ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
                ->pages([
                    \App\Filament\Pages\Dashboard::class,
                ])
                ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
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
                ->plugins([
                    JetstreamPlugin::make()
                        ->profilePhoto()
                        ->deleteAccount()
                        ->updatePassword()
                        ->profileInformation()
                        ->logoutBrowserSessions()
                        ->twoFactorAuthentication(),
                ])
        );
    }

    private function custom(Panel $panel): Panel
    {
        return $panel
            ->topNavigation()
            ->font('Quicksand')
            ->authGuard('web')
            ->navigationItems([
                NavigationItem::make('Introducción')
                    ->url('/dashboard')
                    ->icon('heroicon-o-document-text')
                    ->activeIcon('heroicon-s-document-text')
                    ->isActiveWhen(fn (): bool => request()->routeIs(Dashboard::getRouteName())),
            ]);

    }

    public function boot(): void
    {
        // \Illuminate\Support\Facades\Gate::policy(\Filament\Jetstream\Models\Team::class, \Filament\Jetstream\Policies\TeamPolicy::class);
    }
}
