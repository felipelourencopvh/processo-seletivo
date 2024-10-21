<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class InscricaoDocumento extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $table = 'inscricao_documentos';
    protected $fillable = ['documento', 'enviado', 'aprovado', 'pontuacao', 'pontuacao_previa', 'inscricao_id', 'processo_cargo_documento_id'];
}
