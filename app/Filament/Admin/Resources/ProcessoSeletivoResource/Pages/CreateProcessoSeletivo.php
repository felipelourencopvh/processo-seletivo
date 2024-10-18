<?php

namespace App\Filament\Admin\Resources\ProcessoSeletivoResource\Pages;

use App\Filament\Admin\Resources\ProcessoSeletivoResource;
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
