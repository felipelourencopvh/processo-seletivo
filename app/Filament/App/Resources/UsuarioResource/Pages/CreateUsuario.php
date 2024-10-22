<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\UsuarioResource\Pages;

use App\Filament\App\Resources\UsuarioResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUsuario extends CreateRecord
{
    protected static string $resource = UsuarioResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
