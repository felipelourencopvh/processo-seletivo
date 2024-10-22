<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\UsuarioResource\Pages;

use App\Filament\App\Resources\UsuarioResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListUsuarios extends ListRecords
{
    protected static string $resource = UsuarioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
