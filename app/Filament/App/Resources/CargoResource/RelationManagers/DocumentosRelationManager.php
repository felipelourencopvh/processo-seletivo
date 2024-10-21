<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\CargoResource\RelationManagers;

use App\Models\ProcessoCargoDocumento;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
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
    protected static ?string $title = 'Documentos Necessários';
    protected static string $relationship = 'documentos';
    protected static ?string $label = 'Documento';
    protected static ?string $pluralLabel = 'Documentos';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('documento')
                    ->label('Documento')
                    ->required()
                    ->columnSpanFull()
                    ->live(onBlur: true),
                ToggleButtons::make('obrigatorio')
                    ->label('Obrigatório?')
                    ->boolean()
                    ->required()
                    ->grouped(),
                TextInput::make('pontuacao')
                    ->numeric()
                    ->inputMode('decimal')
                    ->label('Pontuação por Documento')
                    ->required(),
                TextInput::make('quantidade_minima')
                    ->label('Quantidade Mínima de Documentos')
                    ->integer()
                    ->required(),
                TextInput::make('quantidade_maxima')
                    ->integer()
                    ->label('Quantidade Máxima de Documentos')
                    ->required(),

                Section::make([
                    Placeholder::make('created_at')
                        ->label('Criado Em')
                        ->content(fn (?ProcessoCargoDocumento $record): string => $record?->created_at?->diffForHumans() ?? '-'),

                    Placeholder::make('updated_at')
                        ->label('Alterado Em')
                        ->content(fn (?ProcessoCargoDocumento $record): string => $record?->updated_at?->diffForHumans() ?? '-'),
                ]),
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
