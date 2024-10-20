<?php

declare(strict_types=1);

namespace App\Filament\Resources\SecretariaResource\Pages;

use App\Filament\Resources\SecretariaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSecretarias extends ListRecords
{
    protected static string $resource = SecretariaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
