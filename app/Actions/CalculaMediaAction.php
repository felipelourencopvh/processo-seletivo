<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Inscricao;
use Lorisleiva\Actions\Concerns\AsAction;

class CalculaMediaAction
{
    use AsAction;

    public function handle(string $inscricaoId)
    {
        $inscricao = Inscricao::find($inscricaoId);

        $media = $inscricao->documentos()->where('enviado', true)->where('aprovado', true)->sum('pontuacao_previa');
        $mediaPrevia = $inscricao->documentos()->sum('pontuacao_previa');

        return $inscricao->update(['pontuacao' => $media, 'pontuacao_previa' => $mediaPrevia]);
    }
}
