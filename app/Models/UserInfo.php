<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserInfo extends Model
{
    protected $fillable = [
        'documento',
        'data_nascimento',
        'sexo',
        'rg',
        'filiacao_mae',
        'filiacao_pai',
        'telefone_residencial',
        'telefone_celular',
        'endereco_logradouro',
        'endereco_numero',
        'endereco_complemento',
        'endereco_bairro',
        'endereco_cep',
        'endereco_cidade',
        'endereco_uf',
        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
