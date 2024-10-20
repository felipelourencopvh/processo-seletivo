<?php

declare(strict_types=1);

namespace App\Filament\Resources\ProcessoSeletivoResource\Pages;

use App\Filament\Resources\ProcessoSeletivoResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProcessoSeletivo extends CreateRecord
{
    protected static string $resource = ProcessoSeletivoResource::class;

    protected function getRedirectUrl(): string
    {
        return self::getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
