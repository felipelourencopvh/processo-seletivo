<?php

declare(strict_types=1);

namespace App\Providers\Filament;

use App\Filament\App\Pages\Auth\Register;
use App\Filament\App\Pages\EditProcessoSeletivo;
use App\Filament\Auth\MeuPerfil;
use App\Filament\Auth\SigaLogin;
use App\Http\Middleware\ConferirSessaoSiga;
use App\Models\Processo;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Navigation\NavigationItem;
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

class AppPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('app')
            ->path('app')
            ->login(SigaLogin::class)
            ->registration(Register::class)
            ->profile(MeuPerfil::class, isSimple: false)
            ->userMenuItems([
                MenuItem::make()
                    ->label('Administração')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->url('/admin')
                    ->visible(fn (): bool => auth()->user()->hasRole('super_admin')),
            ])
            ->passwordReset()
            ->emailVerification()
            ->discoverResources(in: app_path('Filament/App/Resources'), for: 'App\\Filament\\App\\Resources')
            ->discoverPages(in: app_path('Filament/App/Pages'), for: 'App\\Filament\\App\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/App/Widgets'), for: 'App\\Filament\\App\\Widgets')
            // Indicadores do processo (app/Filament/App/Widgets) no lugar dos widgets padrão do Filament.
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
            ])
            ->authMiddleware([
                Authenticate::class,
                ConferirSessaoSiga::class,
            ])
            ->databaseNotifications()
            ->tenant(Processo::class, slugAttribute: 'slug', ownershipRelationship: 'processo')
            ->tenantProfile(EditProcessoSeletivo::class)
            ->navigationItems([
                NavigationItem::make('Editar Processo Seletivo')
                    ->icon('heroicon-o-rectangle-stack')
                    ->url(static fn () => url(EditProcessoSeletivo::getUrl())),
            ])
            ->viteTheme('resources/css/filament/admin/theme.css')
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
            ->brandName('STX Gov · Recrutamento')
            ->brandLogo(fn () => view('filament.marca'))
            ->brandLogoHeight('2.25rem')
            ->favicon(asset('marca/recrutamento-favicon.svg'));
    }
}
