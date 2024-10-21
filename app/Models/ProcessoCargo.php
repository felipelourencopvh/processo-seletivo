<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\NivelEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProcessoCargo extends Model
{
    protected $fillable = ['nome', 'nivel', 'localidade', 'descricao', 'vagas_ampla', 'vagas_pcd', 'vagas_ppp', 'processo_id'];

    public function processo(): BelongsTo
    {
        return $this->belongsTo(Processo::class);
    }

    public function documentos(): HasMany
    {
        return $this->hasMany(ProcessoCargoDocumento::class, 'processo_cargo_id');
    }

    protected function casts()
    {
        return ['nivel' => NivelEnum::class];
    }
}
