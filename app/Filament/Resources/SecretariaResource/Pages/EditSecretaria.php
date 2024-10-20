<?php

declare(strict_types=1);

namespace App\Filament\Resources\SecretariaResource\Pages;

use App\Filament\Resources\SecretariaResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditSecretaria extends EditRecord
{
    protected static string $resource = SecretariaResource::class;

    protected function getRedirectUrl(): string
    {
        return self::getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
