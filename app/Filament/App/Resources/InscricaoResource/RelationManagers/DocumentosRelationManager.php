<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\InscricaoResource\RelationManagers;

use App\Models\InscricaoDocumento;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DocumentosRelationManager extends RelationManager
{
    protected static string $relationship = 'documentos';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('documento')
                    ->required(),

                Checkbox::make('enviado'),

                Checkbox::make('aprovado'),

                TextInput::make('pontuacao')
                    ->numeric(),

                TextInput::make('pontuacao_previa')
                    ->numeric(),

                TextInput::make('processo_cargo_documento_id')
                    ->required()
                    ->integer(),

                Placeholder::make('created_at')
                    ->label('Created Date')
                    ->content(fn (?InscricaoDocumento $record): string => $record?->created_at?->diffForHumans() ?? '-'),

                Placeholder::make('updated_at')
                    ->label('Last Modified Date')
                    ->content(fn (?InscricaoDocumento $record): string => $record?->updated_at?->diffForHumans() ?? '-'),

                Checkbox::make('registerMediaConversionsUsingModelInstance'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('documento'),

                TextColumn::make('enviado'),

                TextColumn::make('aprovado'),

                TextColumn::make('pontuacao'),

                TextColumn::make('pontuacao_previa'),

                TextColumn::make('processo_cargo_documento_id'),

                TextColumn::make('registerMediaConversionsUsingModelInstance'),
            ])
            ->filters([

            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
