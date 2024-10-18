<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class ProcessoAnexo extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = ['nome', 'tipo', 'data_publicacao', 'processo_id'];

    public function processo(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Processo::class);
    }

    protected function casts()
    {
        return [
            'data_publicacao' => 'date',
        ];
    }
}
