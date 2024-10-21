<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
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

    }
}
