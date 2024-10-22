<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\InscricaoResource\Pages;

use App\Filament\App\Resources\InscricaoResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditInscricao extends EditRecord
{
    protected static string $resource = InscricaoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
