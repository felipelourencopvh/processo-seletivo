<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\InscricaoResource\Pages;

use App\Filament\App\Resources\InscricaoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListInscricaos extends ListRecords
{
    protected static string $resource = InscricaoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
