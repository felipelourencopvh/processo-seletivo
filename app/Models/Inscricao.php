<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Inscricao extends Model
{
    /** @use HasFactory<\Database\Factories\InscricaoFactory> */
    use HasFactory;
    use HasUuids;
    protected $table = 'inscricoes';
    protected $fillable = ['situacao', 'cota', 'pontuacao', 'pontuacao_previa', 'processo_id', 'user_id', 'cargo_id'];

    public function cargo(): BelongsTo
    {
        return $this->belongsTo(ProcessoCargo::class, 'cargo_id', 'id');
    }

    public function processo(): BelongsTo
    {
        return $this->belongsTo(Processo::class);
    }

    public function documentos(): HasMany
    {
        return $this->hasMany(InscricaoDocumento::class, 'inscricao_id', 'id');
    }
}
