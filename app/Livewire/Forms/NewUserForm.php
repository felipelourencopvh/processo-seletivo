<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class NewUserForm extends Form
{
    #[Validate]
    public $name;

    #[Validate]
    public $email;

    public $password;

    public $password_confirmation;

    #[Validate]
    public $documento;

    #[Validate]
    public $data_nascimento;

    #[Validate]
    public $sexo = 'Masculino';

    #[Validate]
    public $rg;

    #[Validate]
    public $filiacao_mae;

    public $filiacao_pai;

    public $telefone_residencial;

    #[Validate]
    public $telefone_celular;

    public $endereco_logradouro;

    public $endereco_numero;

    public $endereco_complemento;

    public $endereco_bairro;

    #[Validate]
    public $endereco_cep;

    public $endereco_cidade;

    public $endereco_uf;

    public function rules()
    {
        return [
            'name' => ['required'],
            'email' => ['required', 'email', 'max:254', Rule::unique('users')],
            'password' => ['required', 'confirmed'],
            'password_confirmation' => ['required'],
            'documento' => ['required', Rule::unique('user_infos'), 'cpf'],
            'data_nascimento' => ['required', 'date'],
            'sexo' => ['required'],
            'rg' => ['required'],
            'filiacao_mae' => ['required'],
            'filiacao_pai' => ['nullable'],
            'telefone_residencial' => ['nullable'],
            'telefone_celular' => ['required'],
            'endereco_logradouro' => ['required'],
            'endereco_numero' => ['required'],
            'endereco_complemento' => ['nullable'],
            'endereco_bairro' => ['required'],
            'endereco_cep' => ['required'],
            'endereco_cidade' => ['required'],
            'endereco_uf' => ['required'],
        ];
    }
}
