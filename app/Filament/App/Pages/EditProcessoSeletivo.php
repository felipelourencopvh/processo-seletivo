<?php

declare(strict_types=1);

namespace App\Filament\App\Pages;

use App\Models\Processo;
use Filament\Forms\Form;
use Filament\Pages\Tenancy\EditTenantProfile;

class EditProcessoSeletivo extends EditTenantProfile
{
    public static function getLabel(): string
    {
        return 'Editar Processo Seletivo';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema(
                Processo::getForm(),
            );
    }
}
