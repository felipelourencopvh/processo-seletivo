<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Inscricao;
use App\Models\InscricaoDocumento;
use App\Models\ProcessoCargo;
use Lorisleiva\Actions\Concerns\AsAction;
use RuntimeException;
use Throwable;

class CreateNewInscricaoAction
{
    use AsAction;

    /** @throws Throwable */
    public function handle(ProcessoCargo $cargo, string $cota): Inscricao
    {
        if (Inscricao::where('processo_id', $cargo->processo_id)->where('user_id', auth()->id())->exists()) {
            throw  new RuntimeException('Já possui inscrição');
        }

        $inscricao              = new Inscricao();
        $inscricao->situacao    = 'Inscrito';
        $inscricao->user_id     = auth()->id();
        $inscricao->processo_id = $cargo->processo_id;
        $inscricao->cargo_id    = $cargo->id;
        $inscricao->cota        = $cota;
        $inscricao->save();

        foreach ($cargo->documentos()->get() as $documento) {
            $pontuacao = (int) $documento->pontuacao;
            for ($x = 1; $x <= $documento->quantidade_maxima; $x++) {
                $inscricaoDoc                              = new InscricaoDocumento();
                $inscricaoDoc->documento                   = $x;
                $inscricaoDoc->enviado                     = false;
                $inscricaoDoc->aprovado                    = false;
                $inscricaoDoc->pontuacao                   = 0;
                $inscricaoDoc->pontuacao_previa            = $pontuacao;
                $inscricaoDoc->inscricao_id                = $inscricao->id;
                $inscricaoDoc->processo_cargo_documento_id = $documento->id;
                $inscricaoDoc->save();

                if (($documento->id === 80) && $pontuacao === 5) {
                    $pontuacao = 10;
                }
            }
        }

        //        try {
        //            Mail::to(auth()->user()->email)->send(new App\Mail\InscricaoConfirmadaMail($inscricao));
        //        } catch (Exception $e) {
        //        }

        return $inscricao;
    }
}
