<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\CargoResource\RelationManagers;

use App\Models\ProcessoCargoDocumento;
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

                Checkbox::make('obrigatorio'),

                TextInput::make('quantidade_minima')
                    ->required()
                    ->integer(),

                TextInput::make('quantidade_maxima')
                    ->required()
                    ->integer(),

                TextInput::make('pontuacao')
                    ->numeric(),

                TextInput::make('ordem')
                    ->required()
                    ->integer(),

                Placeholder::make('created_at')
                    ->label('Created Date')
                    ->content(fn (?ProcessoCargoDocumento $record): string => $record?->created_at?->diffForHumans() ?? '-'),

                Placeholder::make('updated_at')
                    ->label('Last Modified Date')
                    ->content(fn (?ProcessoCargoDocumento $record): string => $record?->updated_at?->diffForHumans() ?? '-'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('documento'),

                TextColumn::make('obrigatorio'),

                TextColumn::make('quantidade_minima'),

                TextColumn::make('quantidade_maxima'),

                TextColumn::make('pontuacao'),

                TextColumn::make('ordem'),
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
