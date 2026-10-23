<?php

declare(strict_types=1);

namespace App\Filament\App\Resources;

use App\Enums\NivelEnum;
use App\Filament\App\Resources\CargoResource\RelationManagers\DocumentosRelationManager;
use App\Models\ProcessoCargo;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
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

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';
    protected static ?string $tenantRelationshipName = 'cargos';

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

                                        Select::make('nivel')->label('Nível de Ensino')
                                            ->options([
                                                'fundamental' => 'Ensino Fundamental',
                                                'medio' => 'Ensino Médio',
                                                'superior' => 'Ensino Superior',
                                            ])
                                            ->required(),

                                        TextInput::make('localidade'),

                                        TextInput::make('descricao')->label('Descrição'),

                                        TextInput::make('vagas_ampla')->label('Vagas de ampla concorrência')
                                            ->integer(),

                                        TextInput::make('vagas_pcd')->label('Vagas PcD')
                                            ->integer(),

                                        TextInput::make('vagas_ppp')->label('Vagas PPP')
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
                        ])->persistTabInQueryString()->columnSpanFull(),
                ],
            );
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nome')->sortable()->searchable(),
                TextColumn::make('localidade')->searchable(),
                TextColumn::make('nivel')
                    ->formatStateUsing(function (NivelEnum $state): string {
                        return $state->getLabel();
                    })->label('Nível de Ensino')->searchable(),
                TextColumn::make('vagas_ampla')->label('Vagas Ampla'),
                TextColumn::make('vagas_pcd')->label('Vagas PcD'),
                TextColumn::make('vagas_ppp')->label('Vagas PPP'),
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
