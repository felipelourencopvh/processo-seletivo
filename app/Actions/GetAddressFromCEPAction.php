<?php

declare(strict_types=1);

namespace App\Actions;

use Illuminate\Support\Facades\Http;

readonly class GetAddressFromCEPAction
{
    public function __construct(
        private string $cep,
    ) {
    }

    public function execute(): mixed
    {
        return Http::get("https://viacep.com.br/ws/{$this->cep}/json/")->json();
    }
}
