<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Models\Secretaria;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SecretariaResource extends Resource
{
    protected static ?string $model = Secretaria::class;

    protected static ?string $slug = 'secretarias';

    protected static ?string $navigationIcon = 'heroicon-o-building-library';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make([
                    TextInput::make('nome')
                        ->required()->unique(ignoreRecord: true),

                    TextInput::make('sigla')
                        ->required()->unique(ignoreRecord: true),
                ]),
                Section::make([
                    Placeholder::make('created_at')
                        ->label('Criado Em')
                        ->content(fn (?Secretaria $record): string => $record?->created_at?->diffForHumans() ?? '-'),

                    Placeholder::make('updated_at')
                        ->label('Alterado Em')
                        ->content(fn (?Secretaria $record): string => $record?->updated_at?->diffForHumans() ?? '-'),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nome'),

                TextColumn::make('sigla'),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
                RestoreAction::make(),
                ForceDeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => SecretariaResource\Pages\ListSecretarias::route('/'),
            'create' => SecretariaResource\Pages\CreateSecretaria::route('/create'),
            'edit' => SecretariaResource\Pages\EditSecretaria::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [];
    }
}
