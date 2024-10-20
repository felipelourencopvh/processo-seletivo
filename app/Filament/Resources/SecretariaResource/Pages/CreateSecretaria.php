<?php

declare(strict_types=1);

namespace App\Filament\Resources\SecretariaResource\Pages;

use App\Filament\Resources\SecretariaResource;
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
