<?php

declare(strict_types=1);

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\InscricaoResource\RelationManagers\DocumentosRelationManager;
use App\Filament\Exports\InscricaoExporter;
use App\Models\Inscricao;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Facades\Filament;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\HtmlString;

class InscricaoResource extends Resource
{
    protected static ?string $model = Inscricao::class;

    protected static ?string $slug = 'inscricoes';
    protected static ?string $label = 'Inscrição';
    protected static ?string $pluralLabel = 'Inscrições';

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make([
                    Placeholder::make('user.name')
                        ->content(
                            fn (Inscricao $record) => new HtmlString(
                                '<a class="underline" href="'.route(
                                    'filament.app.resources.app.resources.usuarios.edit',
                                    ['record' => $record->user_id, 'tenant' => Filament::getTenant()],
                                ).'">'.$record->user->name.'</a>',
                            ),
                        ),
                    Select::make('situacao')->label('Situação')
                        ->options([
                            'Pendente' => 'Pendente',
                            'Homologado' => 'Homologado',
                            'Reprovado' => 'Reprovado',
                            'Aprovado' => 'Aprovado',
                        ])
                        ->required(),

                    Select::make('cota')->label('Cota')
                        ->options([
                            'ampla' => 'Ampla Concorrência',
                            'pcd' => 'Pessoa com Deficiência',
                            'ppp' => 'Pessoa Preta ou Parda',
                        ])->disabled()
                        ->required(),

                    TextInput::make('pontuacao')
                        ->numeric(),
                ]),
                Section::make([
                    Placeholder::make('created_at')
                        ->label('Criado Em')
                        ->content(fn (?Inscricao $record): string => $record?->created_at?->diffForHumans() ?? '-'),

                    Placeholder::make('updated_at')
                        ->label('Alterado Em')
                        ->content(fn (?Inscricao $record): string => $record?->updated_at?->diffForHumans() ?? '-'),
                ]),
            ])->columns('full');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')->label('Nome')->searchable()->sortable(),
                TextColumn::make('user.info.documento')->label('CPF')->searchable()->sortable(),
                TextColumn::make('situacao')->label('Situação')->badge()->color(fn (string $state): string => match ($state) {
                    'Pendente' => 'info',
                    'Homologado' => 'warning',
                    'Reprovado' => 'success',
                    'Aprovado' => 'danger',
                })->searchable()->sortable(),
                TextColumn::make('cargo.nome')->label('Cargo')->searchable()->sortable(),
                TextColumn::make('cargo.localidade')->label('Localidade')->searchable()->sortable(),
                TextColumn::make('cota')->searchable()->sortable(),
                TextColumn::make('pontuacao')->numeric(),
                TextColumn::make('pontuacao_previa')->numeric(),
            ])
            ->headerActions(
                [
                    ExportAction::make()->exporter(InscricaoExporter::class)->formats([
                        ExportFormat::Xlsx,
                        ExportFormat::Csv,
                    ]),
                ],
            )
            ->filters([
                TrashedFilter::make(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ExportAction::make()->exporter(InscricaoExporter::class)->formats([
                        ExportFormat::Xlsx,
                        ExportFormat::Csv,
                    ]),
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
            'index' => InscricaoResource\Pages\ListInscricaos::route('/'),
            'create' => InscricaoResource\Pages\CreateInscricao::route('/create'),
            'edit' => InscricaoResource\Pages\EditInscricao::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [];
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::count();
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
