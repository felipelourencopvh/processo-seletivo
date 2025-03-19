<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Inscricao extends Model
{
    use LogsActivity;
    use SoftDeletes;
    /** @use HasFactory<\Database\Factories\InscricaoFactory> */
    use HasFactory;
    use HasUuids;
    protected $table    = 'inscricoes';
    protected $fillable = ['situacao', 'cota', 'pontuacao', 'pontuacao_previa', 'processo_id', 'user_id', 'cargo_id', 'observacao', 'recurso', 'analise_recurso', 'criterio_classificacao', 'criterio_inscricao'];

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
        return $this->hasMany(InscricaoDocumento::class, 'inscricao_id', 'id')->orderBy('id', 'asc');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll();
    }
}
