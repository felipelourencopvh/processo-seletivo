<?php

declare(strict_types=1);

namespace App\Filters;

use Illuminate\Support\Collection;

readonly class AlocacaoFilter
{
    public function __construct(public readonly Collection|array $ids)
    {
    }

    public static function minhasSecretarias(): self
    {
        return new self(auth()->user()?->listaIdsSecretarias() ?? []);
    }

    public static function meusDepartamentos(): self
    {
        return new self(auth()->user()?->listaIdsSecretarias() ?? []);
    }
}
