<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\InscricaoResource\RelationManagers;

use App\Actions\CalculaMediaAction;
use App\Models\InscricaoDocumento;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\Summarizers\Count;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Enums\ActionsPosition;
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
                TextColumn::make('cargoDocumento.documento')->limit(50),
                ToggleColumn::make('aprovado')
                    ->beforeStateUpdated(function (InscricaoDocumento $record, $state) {
                        $record->pontuacao = $state ? $record->pontuacao_previa : 0;
                    })
                    ->afterStateUpdated(function (InscricaoDocumento $record, $state) {
                        CalculaMediaAction::run($record->inscricao_id);
                    }),
                TextColumn::make('pontuacao_previa')->label('Pontuação Prévia')->summarize(
                    Count::make('Total Prévio')->label('Total Prévio')->query(fn ($query) => $query->where('enviado', true)),
                ),
                TextColumn::make('pontuacao')->label('Pontuação Final')->summarize(
                    Count::make('Total Final')->label('Total Final')->query(fn ($query) => $query->where('enviado', true)->where('aprovado', true)),
                ),
            ])
            ->actions([
                Action::make('Ver Documento')
                    ->icon('heroicon-o-server')
                    ->hidden(fn (InscricaoDocumento $record) => ! $record->enviado)
                    ->url(fn (InscricaoDocumento $record): string => $record->getFirstMediaUrl())
                    ->openUrlInNewTab(),
            ], position: ActionsPosition::BeforeColumns)
            ->bulkActions([
            ])->paginated(false);
    }
}
