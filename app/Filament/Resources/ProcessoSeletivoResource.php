<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Enums\ProcessoStatus;
use App\Models\Processo;
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

class ProcessoSeletivoResource extends Resource
{
    protected static ?string $model = Processo::class;

    protected static ?string $slug = 'processo-seletivos';
    protected static ?string $label = 'Processo Seletivo';
    protected static ?string $pluralLabel = 'Processos Seletivos';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(Processo::getForm());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('titulo'),
                TextColumn::make('status')->label('Situação')
                    ->badge()
                    ->color(fn (ProcessoStatus $state): string => $state->getColor()),

                TextColumn::make('data_abertura')
                    ->date('d/m/Y H:i'),

                TextColumn::make('data_resultado')
                    ->date('d/m/Y'),

                TextColumn::make('data_suspensao_parcial')
                    ->date('d/m/Y H:i'),

                TextColumn::make('data_suspensao_integral')
                    ->date('d/m/Y H:i'),
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
            'index' => ProcessoSeletivoResource\Pages\ListProcessoSeletivos::route('/'),
            'create' => ProcessoSeletivoResource\Pages\CreateProcessoSeletivo::route('/create'),
            'edit' => ProcessoSeletivoResource\Pages\EditProcessoSeletivo::route('/{record}/edit'),
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
        return ['slug'];
    }
}
