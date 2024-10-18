<?php

namespace App\Filament\Admin\Resources\SecretariaResource\Pages;

use App\Filament\Admin\Resources\SecretariaResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSecretaria extends CreateRecord
{
    protected static string $resource = SecretariaResource::class;

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
