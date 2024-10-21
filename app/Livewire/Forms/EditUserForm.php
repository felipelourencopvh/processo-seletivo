<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use Livewire\Form;

class EditUserForm extends Form
{
    public $name = '';

    public $email = '';

    public $documento = '';

    public $data_nascimento = '';

    public $sexo = 'Masculino';

    public $rg = '';

    public $filiacao_mae = '';
    public $filiacao_pai = '';

    public $telefone_residencial = '';
    public $telefone_celular = '';

    public $endereco_logradouro = '';
    public $endereco_numero = '';
    public $endereco_complemento = '';
    public $endereco_bairro = '';
    public $endereco_cep = '';
    public $endereco_cidade = '';
    public $endereco_uf = '';

    public ?string $password = '';

    public ?string $password_confirmation = '';

    public function rules()
    {
        return [
            'name' => ['required', 'required'],
            'email' => ['required', 'email'],
            'documento' => ['required'],
            'data_nascimento' => ['required', 'date'],
            'sexo' => ['required'],
            'rg' => ['required'],
            'filiacao_mae' => ['required'],
            'filiacao_pai' => ['nullable'],
            'telefone_residencial' => ['nullable'],
            'telefone_celular' => ['required'],
            'endereco_logradouro' => ['required'],
            'endereco_numero' => ['nullable'],
            'endereco_complemento' => ['nullable'],
            'endereco_bairro' => ['required'],
            'endereco_cep' => ['required'],
            'endereco_cidade' => ['required'],
            'endereco_uf' => ['required'],
        ];
    }

    public function validateChangePassword()
    {
        return $this->validate([
            'password' => ['nullable', 'confirmed'],
            'password_confirmation' => ['nullable'],
        ]);
    }
}
