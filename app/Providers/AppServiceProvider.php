<?php

declare(strict_types=1);

namespace App\Providers;

use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /** Register any application services. */
    public function register(): void
    {

    }

    /** Bootstrap any application services. */
    public function boot(): void
    {
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
