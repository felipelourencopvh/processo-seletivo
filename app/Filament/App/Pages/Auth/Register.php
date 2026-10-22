<?php

declare(strict_types=1);

namespace App\Filament\App\Pages\Auth;

use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Events\Auth\Registered;
use Filament\Facades\Filament;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Http\Responses\Auth\Contracts\RegistrationResponse;
use Filament\Pages\Auth\Register as BaseRegister;
use App\Filament\Forms\Cep;
use Leandrocfe\FilamentPtbrFormFields\Document;
use Leandrocfe\FilamentPtbrFormFields\PhoneNumber;

class Register extends BaseRegister
{
    public function mount(): void
    {
        if (Filament::auth()->check()) {
            redirect()->intended('/');
        }

        $this->callHook('beforeFill');

        $this->form->fill();

        $this->callHook('afterFill');
    }

    public function register(): ?RegistrationResponse
    {
        try {
            $this->rateLimit(2);
        } catch (TooManyRequestsException $exception) {
            $this->getRateLimitedNotification($exception)?->send();

            return null;
        }

        $user = $this->wrapInDatabaseTransaction(function () {
            $this->callHook('beforeValidate');

            $data = $this->form->getState();

            $this->callHook('afterValidate');

            $data = $this->mutateFormDataBeforeRegister($data);

            $this->callHook('beforeRegister');

            $user = $this->handleRegistration($data);

            // Salvar os relacionamentos básicos primeiro
            $this->form->model($user)->saveRelationships();

            // Salvar o relacionamento "info" manualmente
            if (isset($data['info'])) {
                $user->info()->updateOrCreate([], $data['info']);
            }

            $this->callHook('afterRegister');

            return $user;
        });

        event(new Registered($user));

        $this->sendEmailVerificationNotification($user);

        Filament::auth()->login($user);

        session()->regenerate();

        return app(RegistrationResponse::class);
    }

    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this
                    ->makeForm()
                    ->schema([
                        Fieldset::make('Dados de Acesso')->schema([
                            $this->getNameFormComponent(),
                            $this->getEmailFormComponent(),
                            $this->getPasswordFormComponent(),
                            $this->getPasswordConfirmationFormComponent(),
                        ])->columns(1),
                        Fieldset::make('Dados Pessoais')->schema([
                            Document::make('info.documento')->label('CPF')->cpf()->required(),
                            DatePicker::make('info.data_nascimento')->label('Data de Nascimento')->required(),
                            Select::make('info.sexo')->label('Sexo')
                                ->options([
                                    'masculino' => 'Masculino',
                                    'feminino' => 'Feminino',
                                ])->required(),
                            TextInput::make('info.rg')->label('Número do RG')->required(),
                            TextInput::make('info.filiacao_mae')->label('Nome da Mãe')->required(),
                            TextInput::make('info.filiacao_pai')->label('Nome do Pai'),
                            PhoneNumber::make('info.telefone_residencial')->label('Tel. Residencial')->mask('(99) 99999-9999'),
                            PhoneNumber::make('info.telefone_celular')->label('Tel. Celular')->mask('(99) 99999-9999')->required(),
                        ])->columns(1),
                        Fieldset::make('Endereço')
                            ->schema([
                                Cep::make('info.endereco_cep')->label('CEP')->required()
                                    ->baseCep([
                                        'info.endereco_logradouro' => 'logradouro',
                                        'info.endereco_numero' => 'numero',
                                        'info.endereco_complemento' => 'complemento',
                                        'info.endereco_bairro' => 'bairro',
                                        'info.endereco_cidade' => 'localidade',
                                        'info.endereco_uf' => 'uf',
                                    ]),
                                TextInput::make('info.endereco_logradouro')->label('Logradouro')->required(),
                                TextInput::make('info.endereco_complemento')->label('Complemento'),
                                TextInput::make('info.endereco_numero')->label('Número'),
                                TextInput::make('info.endereco_bairro')->label('Bairro'),
                                TextInput::make('info.endereco_cidade')->label('Cidade')->required(),
                                TextInput::make('info.endereco_uf')->label('UF')->required(),
                            ])->columns(1),
                    ])
                    ->statePath('data'),
            ),
        ];
    }
}
