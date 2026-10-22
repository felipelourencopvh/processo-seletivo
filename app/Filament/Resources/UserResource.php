<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;
use App\Filament\Forms\Cep;
use Leandrocfe\FilamentPtbrFormFields\Document;
use Leandrocfe\FilamentPtbrFormFields\PhoneNumber;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $slug = 'usuarios';
    protected static ?string $label = 'Usuário';
    protected static ?string $pluralLabel = 'Usuários';
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Gerenciamento';

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return static::getModel()::count() > 10 ? 'warning' : 'success';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Dados de Acesso')->schema([
                    TextInput::make('name')->label('Nome')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('email')->label('E-mail')
                        ->email()
                        ->required()
                        ->maxLength(255),
                    TextInput::make('password')->label('Senha')
                        ->password()
                        ->maxLength(255)
                        ->dehydrateStateUsing(static function ($state, $record) {
                            return ! empty($state)
                                ? Hash::make($state)
                                : $record->password;
                        }),
                    Select::make('roles')->label('Perfis')
                        ->relationship('roles', 'name')
                        ->multiple()
                        ->preload()
                        ->searchable(),
                    Select::make('processos')->label('Gerenciamento de Processos Seletivos')
                        ->relationship('processos', 'titulo')
                        ->multiple()
                        ->preload()
                        ->searchable(),
                ]),
                Section::make('Dados Pessoais')->schema([
                    Fieldset::make()->schema([
                        Document::make('documento')->label('CPF')->cpf()->required(),
                        DatePicker::make('data_nascimento')->label('Data de Nascimento'),
                        Select::make('sexo')->label('Sexo')
                            ->options([
                                'masculino' => 'Masculino',
                                'feminino' => 'Feminino',
                            ]),
                        TextInput::make('rg')->label('Número do RG'),
                        TextInput::make('filiacao_mae')->label('Nome da Mãe'),
                        TextInput::make('filiacao_pai')->label('Nome do Pai'),
                        PhoneNumber::make('telefone_residencial')->label('Tel. Residencial')->mask('(99) 99999-9999'),
                        PhoneNumber::make('telefone_celular')->label('Tel. Celular')->mask('(99) 99999-9999'),
                    ]),
                    Fieldset::make('Endereço')
                        ->schema([
                            Cep::make('endereco_cep')
                                ->baseCep([
                                    'endereco_logradouro' => 'logradouro',
                                    'endereco_numero' => 'numero',
                                    'endereco_complemento' => 'complemento',
                                    'endereco_bairro' => 'bairro',
                                    'endereco_cidade' => 'localidade',
                                    'endereco_uf' => 'uf',
                                ]),

                            TextInput::make('endereco_logradouro')->label('Logradouro')->columnSpanFull(),
                            TextInput::make('endereco_complemento')->label('Complemento')->columnSpanFull(),
                            TextInput::make('endereco_numero')->label('Número'),
                            TextInput::make('endereco_bairro')->label('Bairro'),
                            TextInput::make('endereco_cidade')->label('Cidade'),
                            TextInput::make('endereco_uf')->label('UF'),
                        ]),
                ])->relationship('info')->label('Dados Pessoais'),
                Section::make([
                    Placeholder::make('created_at')
                        ->label('Criado Em')
                        ->content(fn (?User $record): string => $record?->created_at?->diffForHumans() ?? '-'),

                    Placeholder::make('updated_at')
                        ->label('Alterado Em')
                        ->content(fn (?User $record): string => $record?->updated_at?->diffForHumans() ?? '-'),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
