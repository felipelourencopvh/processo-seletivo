<?php

declare(strict_types=1);

namespace App\Providers;

use App\Mail\SigaTransport;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /** Register any application services. */
    public function register(): void
    {

    }

    /** Bootstrap any application services. */
    public function boot(): void
    {
        // E-mail pela Central de avisos do SIGA (MAIL_MAILER=siga).
        Mail::extend('siga', fn () => new SigaTransport());
        Str::macro('cpfCnpjMask', static function (?string $str) {
            if (empty($str)) {
                return null;
            }

            if (strlen($str) === 18) {
                return '**' . substr($str, 2, 9) . '****-**';
            }

            return '***' . substr($str, 3, 9) . '**';
        });

        Str::macro('cpfReplace', static function (?string $str) {
            // Regex para encontrar CPF no formato 999.999.999-99
            $regex = '/\b\d{3}\.\d{3}\.\d{3}-\d{2}\b/';

            // Substituindo todos os CPFs encontrados utilizando a função cpfCnpjMask
            return preg_replace_callback($regex, static function ($matches) {
                return Str::cpfCnpjMask($matches[0]);
            }, $str);
        });

        Str::macro('toBrl', static function ($state = null) {
            if (blank($state)) {
                return null;
            }

            if ( ! is_numeric($state)) {
                return $state;
            }

            return number_format($state, 2, ',', '.');
        });

        Builder::macro('pluckWithIdName', fn ($idColumn = null, $nameColumn = null): Collection => $this->pluck($nameColumn, $idColumn)->map(fn ($value, $id): array => ['id' => $id, 'name' => $value])->values());

        // Usuário e Sair ficam na barra superior (padrão do ecossistema STX Gov), não no rodapé do menu.
        FilamentView::registerRenderHook(
            PanelsRenderHook::USER_MENU_BEFORE,
            fn () => view('filament.topbar-end')
        );

        FilamentView::registerRenderHook(
            PanelsRenderHook::TOPBAR_START,
            fn () => Blade::render('@livewire(App\Livewire\Filament\TopBarStart::class)')
        );
    }
}
