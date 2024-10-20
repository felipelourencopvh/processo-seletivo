<?php

declare(strict_types=1);

namespace App\Filament\Resources\ProcessoSeletivoResource\Pages;

use App\Filament\Resources\ProcessoSeletivoResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditProcessoSeletivo extends EditRecord
{
    protected static string $resource = ProcessoSeletivoResource::class;

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
