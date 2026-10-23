<?php

declare(strict_types=1);

namespace App\Providers\Filament;

use App\Filament\Auth\MeuPerfil;
use App\Filament\Auth\SigaLogin;
use App\Http\Middleware\ConferirSessaoSiga;
use App\Http\Middleware\VerifyIsAdmin;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\FontProviders\GoogleFontProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('admin')
            ->path('admin')
            ->brandName('STX Gov · Recrutamento')
            ->brandLogo(fn () => view('filament.marca'))
            ->brandLogoHeight('2.25rem')
            ->favicon(asset('marca/recrutamento-favicon.svg'))
            ->passwordReset()
            ->emailVerification()
            ->profile(MeuPerfil::class, isSimple: false)
            ->login(SigaLogin::class)
            ->colors([
                'danger'  => Color::Red,
                'gray'    => Color::Slate,
                'info'    => Color::Blue,
                'primary' => Color::Pink,
                'success' => Color::Emerald,
                'warning' => Color::Orange,
            ])
            ->darkMode(false)
            ->font('Inter Tight', provider: GoogleFontProvider::class)
            ->userMenuItems([
                MenuItem::make()
                    ->label('Painel da comissão')
                    ->icon('heroicon-o-clipboard-document-check')
                    ->url('/app'),
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->discoverClusters(in: app_path('Filament/Clusters'), for: 'App\\Filament\\Clusters')
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->pages([
                Pages\Dashboard::class,
            ])
            // Os indicadores do recrutamento (app/Filament/Widgets) no lugar dos widgets padrão do Filament.
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
                VerifyIsAdmin::class,
            ])
            ->authMiddleware([
                Authenticate::class,
                ConferirSessaoSiga::class,
            ])
            ->plugins([
                FilamentShieldPlugin::make()
                    ->gridColumns([
                        'default' => 1,
                        'sm'      => 2,
                        'lg'      => 2,
                    ])
                    ->sectionColumnSpan(1)
                    ->checkboxListColumns([
                        'default' => 1,
                        'sm'      => 2,
                        'lg'      => 4,
                    ])
                    ->resourceCheckboxListColumns([
                        'default' => 1,
                        'sm'      => 2,
                    ]),

            ])
            ->databaseNotifications()
            ->viteTheme('resources/css/filament/admin/theme.css');
    }
}
