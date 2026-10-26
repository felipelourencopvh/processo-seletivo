<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Siga\SigaException;
use App\Services\Siga\SigaOidc;
use Filament\Facades\Filament;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Entrar no backoffice (painéis Filament /admin e /app) pelo SIGA. A pessoa é reconhecida pelo CPF;
 * o que ela pode fazer são os papéis daqui. O lado do candidato (Volt) não usa o SIGA.
 */
class SigaLoginController extends Controller
{
    public function __construct(private readonly SigaOidc $siga) {}

    public function entrar(Request $request): RedirectResponse
    {
        abort_unless($this->siga->configurado(), 404);

        return redirect()->away($this->siga->urlEntrada($request));
    }

    public function retorno(Request $request): RedirectResponse|Response
    {
        abort_unless($this->siga->configurado(), 404);

        try {
            $pessoa = $this->siga->concluir($request);
        } catch (SigaException $e) {
            return $this->semAcesso('Não foi possível entrar', $e->getMessage(), 400);
        }

        $usuario = User::query()->where('cpf', $pessoa['cpf'])->first();
        $quem = $pessoa['nome'] ?: 'Você';

        // O backoffice global (/admin) não é escopado por Processo: é onde todo administrador entra.
        $painel = Filament::getPanel('admin');

        if (! $usuario) {
            return $this->semAcesso('Sem acesso ao Recrutamento', "{$quem} entrou no SIGA, mas não tem acesso a este sistema. Peça ao administrador do SIGA.", 403);
        }
        if (! ($usuario->getAttribute('ativo') ?? true)) {
            return $this->semAcesso('Sem acesso ao Recrutamento', "{$quem} entrou no SIGA, mas o seu usuário está inativo neste sistema. Peça ao administrador do SIGA.", 403);
        }
        if (! $usuario->canAccessPanel($painel)) {
            return $this->semAcesso('Sem acesso ao Recrutamento', "{$quem} entrou no SIGA, mas não tem permissão no backoffice. Peça ao administrador do SIGA.", 403);
        }

        Auth::guard('web')->login($usuario);
        $request->session()->put('siga.id_token', $pessoa['id_token']);
        $request->session()->put('siga.access_token', $pessoa['access_token']);
        $request->session()->put('siga.conferido_em', now()->timestamp);

        // Cai sempre no backoffice global (/admin), que funciona mesmo sem nenhum processo criado. Pela rota do painel,
        // não por $painel->getUrl(): fora de uma tela do Filament, getUrl() monta o menu do painel "atual" (o /app, que
        // exige o processo na URL) e dava erro 500 ao entrar pelo SIGA.
        return redirect()->intended(route('filament.admin.pages.dashboard'));
    }

    private function semAcesso(string $titulo, string $mensagem, int $status): Response
    {
        return response()->view('auth.siga-sem-acesso', [
            'titulo' => $titulo,
            'mensagem' => $mensagem,
            'portal' => config('services.siga.url'),
        ], $status);
    }
}
