<?php

namespace App\Filament\Admin\Resources\ProcessoSeletivoResource\Pages;

use App\Filament\Admin\Resources\ProcessoSeletivoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProcessoSeletivos extends ListRecords
{
    protected static string $resource = ProcessoSeletivoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
