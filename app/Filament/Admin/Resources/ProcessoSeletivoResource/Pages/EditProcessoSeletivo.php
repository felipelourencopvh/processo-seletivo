<?php

namespace App\Filament\Admin\Resources\ProcessoSeletivoResource\Pages;

use App\Filament\Admin\Resources\ProcessoSeletivoResource;
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
