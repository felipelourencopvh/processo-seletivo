<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\UsuarioResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class InscricoesRelationManager extends RelationManager
{
    protected static string $relationship = 'inscricoes';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('user.name')->label('Nome'),
                TextColumn::make('user.info.documento')->label('CPF'),
                TextColumn::make('situacao')->label('Situação'),
                TextColumn::make('cargo.nome')->label('Cargo'),
                TextColumn::make('cota'),
            ])
            ->filters([

            ])
            ->actions([
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
