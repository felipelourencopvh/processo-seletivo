<?php

declare(strict_types=1);

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\CargoResource\RelationManagers\DocumentosRelationManager;
use App\Models\ProcessoCargo;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\Alignment;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CargoResource extends Resource
{
    protected static ?string $model = ProcessoCargo::class;

    protected static ?string $slug = 'cargos';
    protected static ?string $label = 'Cargo';
    protected static ?string $pluralLabel = 'Cargos';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(
                [
                    Tabs::make('Tabs')
                        ->tabs([
                            Tabs\Tab::make('Informações Gerais')
                                ->schema([
                                    Section::make([
                                        TextInput::make('nome')
                                            ->required(),

                                        TextInput::make('nivel'),

                                        TextInput::make('localidade'),

                                        TextInput::make('descricao'),

                                        TextInput::make('vagas_ampla')
                                            ->integer(),

                                        TextInput::make('vagas_pcd')
                                            ->integer(),

                                        TextInput::make('vagas_ppp')
                                            ->integer(),

                                        TextInput::make('processo_id')
                                            ->required()
                                            ->integer(),
                                    ]),
                                    Section::make([
                                        Placeholder::make('created_at')
                                            ->label('Criado Em')
                                            ->content(fn (?ProcessoCargo $record): string => $record?->created_at?->diffForHumans() ?? '-'),

                                        Placeholder::make('updated_at')
                                            ->label('Alterado Em')
                                            ->content(fn (?ProcessoCargo $record): string => $record?->updated_at?->diffForHumans() ?? '-'),
                                    ]),
                                ]),
                            Tabs\Tab::make('Documentos Necessários')
                                ->schema([
                                    Repeater::make('documentos')->label('Documentos Necessários')
                                        ->schema([
                                            TextInput::make('documento')->label('Documento')
                                                ->required()
                                                ->live(onBlur: true),
                                            ToggleButtons::make('obrigatorio')
                                                ->label('Obrigatório?')
                                                ->boolean()
                                                ->required()
                                                ->grouped(),
                                            TextInput::make('quantidade_minima')
                                                ->label('Quantidade Mínima de Documentos')
                                                ->integer()
                                                ->required(),
                                            TextInput::make('quantidade_maxima')
                                                ->integer()
                                                ->label('Quantidade Máxima de Documentos')
                                                ->required(),
                                            TextInput::make('pontuacao')
                                                ->numeric()
                                                ->inputMode('decimal')
                                                ->label('Pontuação por Documento')
                                                ->required(),
                                        ])
                                        ->columns(1)
                                        ->itemLabel(fn (array $state): ?string => $state['documento'] ?? null)
                                        ->defaultItems(0)
                                        ->addActionAlignment(Alignment::Start)
                                        ->addActionLabel('+ Documentos')->orderColumn('ordem'),
                                ]),
                        ])->persistTabInQueryString()->columnSpanFull(),
                ]
            );
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nome'),

                TextColumn::make('nivel'),

                TextColumn::make('localidade'),

                TextColumn::make('descricao'),

                TextColumn::make('vagas_ampla'),

                TextColumn::make('vagas_pcd'),

                TextColumn::make('vagas_ppp'),

                TextColumn::make('processo_id'),
            ])
            ->filters([

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

    public static function getRelations(): array
    {
        return [
            DocumentosRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => CargoResource\Pages\ListCargos::route('/'),
            'create' => CargoResource\Pages\CreateCargo::route('/create'),
            'edit' => CargoResource\Pages\EditCargo::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [];
    }
}
