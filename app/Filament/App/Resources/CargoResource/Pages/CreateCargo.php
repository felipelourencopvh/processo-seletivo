<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\CargoResource\Pages;

use App\Filament\App\Resources\CargoResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCargo extends CreateRecord
{
    protected static string $resource = CargoResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
