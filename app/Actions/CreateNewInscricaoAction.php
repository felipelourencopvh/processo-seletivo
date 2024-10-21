<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Inscricao;
use App\Models\InscricaoDocumento;
use App\Models\ProcessoCargo;
use App\Models\User;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class CreateNewInscricaoAction
{
    use AsAction;

    /** @throws Throwable */
    public function handle(User $usuario, ProcessoCargo $cargo, string $cota): Inscricao
    {
        throw_if($usuario->inscricoes()->where('processo_id', $cargo->processo_id)->exists(), 'Já possui uma inscrição');

        $inscricao = new Inscricao();
        $inscricao->situacao = 'Pendente';
        $inscricao->user_id = auth()->id();
        $inscricao->processo_id = $cargo->processo_id;
        $inscricao->cargo_id = $cargo->id;
        $inscricao->cota = $cota;
        $inscricao->save();

        foreach ($cargo->documentos()->get() as $documento) {
            for ($x = 1; $x <= $documento->quantidade_maxima; $x++) {
                $inscricaoDoc = new InscricaoDocumento();
                $inscricaoDoc->documento = $x;
                $inscricaoDoc->enviado = false;
                $inscricaoDoc->aprovado = false;
                $inscricaoDoc->pontuacao = 0;
                $inscricaoDoc->pontuacao_previa = $documento->pontuacao;
                $inscricaoDoc->inscricao_id = $inscricao->id;
                $inscricaoDoc->processo_cargo_documento_id = $documento->id;
                $inscricaoDoc->save();
            }
        }

        //        try {
        //            Mail::to(auth()->user()->email)->send(new App\Mail\InscricaoConfirmadaMail($inscricao));
        //        } catch (Exception $e) {
        //        }

        return $inscricao;
    }
}
