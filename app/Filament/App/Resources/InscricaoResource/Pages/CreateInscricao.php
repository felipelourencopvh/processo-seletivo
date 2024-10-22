<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\InscricaoResource\Pages;

use App\Filament\App\Resources\InscricaoResource;
use Filament\Resources\Pages\CreateRecord;

class CreateInscricao extends CreateRecord
{
    protected static string $resource = InscricaoResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
