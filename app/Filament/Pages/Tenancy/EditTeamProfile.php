<?php

namespace App\Filament\Pages\Tenancy;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Pages\Tenancy\EditTenantProfile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class EditTeamProfile extends EditTenantProfile
{
    public static function getLabel(): string
    {
        return 'Secretaria';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->label('Nome')->required()->live(true)->afterStateUpdated(function (
                    Set $set,
                    ?string $state
                ) {
                    return $set('slug', Str::slug($state));
                }),
                TextInput::make('slug')->label('Slug')->maxLength(255)->unique('teams', 'slug', null, 'id'),
            ]);
    }
}
