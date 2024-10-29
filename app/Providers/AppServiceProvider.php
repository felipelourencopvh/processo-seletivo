<?php

declare(strict_types=1);

namespace App\Providers;

use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;
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
        Str::macro('cpfCnpjMask', static function (?string $str) {
            if (empty($str)) {
                return null;
            }

            if (strlen($str) === 18) {
                return '**'.substr($str, 2, 9).'****-**';
            }

            return '***'.substr($str, 3, 9).'**';
        });

        Str::macro('cpfReplace', static function (?string $str) {
            // Regex para encontrar CPF no formato 999.999.999-99
            $regex = '/\b\d{3}\.\d{3}\.\d{3}-\d{2}\b/';

            // Substituindo todos os CPFs encontrados utilizando a função cpfCnpjMask
            return preg_replace_callback($regex, static function ($matches) {
                return Str::cpfCnpjMask($matches[0]);
            }, $str);
        });

        Builder::macro('pluckWithIdName', fn ($idColumn = null, $nameColumn = null): Collection => $this->pluck($nameColumn, $idColumn)->map(fn ($value, $id): array => ['id' => $id, 'name' => $value])->values());

        FilamentView::registerRenderHook(
            PanelsRenderHook::SIDEBAR_FOOTER,
            fn () => view('filament.sidebar-nav-end')
        );

        FilamentView::registerRenderHook(
            PanelsRenderHook::TOPBAR_START,
            fn () => Blade::render('@livewire(App\Livewire\Filament\TopBarStart::class)')
        );
    }
}
