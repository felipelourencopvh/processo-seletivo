<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProcessoCargoDocumento extends Model
{
    protected $fillable = ['documento', 'obrigatorio', 'quantidade_minima', 'quantidade_maxima', 'pontuacao', 'ordem', 'processo_cargo_id'];

    public function cargo(): BelongsTo
    {
        return $this->belongsTo(ProcessoCargo::class, 'processo_cargo_id', 'id');
    }
}
