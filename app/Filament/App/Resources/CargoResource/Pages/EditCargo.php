<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\CargoResource\Pages;

use App\Filament\App\Resources\CargoResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCargo extends EditRecord
{
    protected static string $resource = CargoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
