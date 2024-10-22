<?php

declare(strict_types=1);

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\InscricaoResource\RelationManagers\DocumentosRelationManager;
use App\Models\Inscricao;
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
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

class InscricaoResource extends Resource
{
    protected static ?string $model = Inscricao::class;

    protected static ?string $slug = 'inscricoes';
    protected static ?string $label = 'Inscrição';
    protected static ?string $pluralLabel = 'Inscrições';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Placeholder::make('user.name')
                    ->content(fn (Inscricao $record) => new HtmlString('<a class="underline" href="'.route('filament.app.resources.app.resources.usuarios.edit', ['record' => $record->user_id, 'tenant' => Filament::getTenant()]).'">'.$record->user->name. '</a>')),

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
                    ])
                    ->required(),

                TextInput::make('pontuacao')
                    ->numeric(),

                TextInput::make('pontuacao_previa')
                    ->numeric(),

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
                TextColumn::make('user.name')->label('Nome'),
                TextColumn::make('user.info.documento')->label('CPF'),
                TextColumn::make('situacao')->label('Situação'),
                TextColumn::make('cargo.nome')->label('Cargo'),
                TextColumn::make('cota'),
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
            'index' => InscricaoResource\Pages\ListInscricaos::route('/'),
            'create' => InscricaoResource\Pages\CreateInscricao::route('/create'),
            'edit' => InscricaoResource\Pages\EditInscricao::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [];
    }
}
