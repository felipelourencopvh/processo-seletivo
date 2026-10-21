<?php

declare(strict_types=1);

namespace App\Services\Siga;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

/**
 * STX Gov · Recrutamento entra pelo login do SIGA (OpenID Connect, cliente público com PKCE),
 * no mesmo padrão dos demais satélites do ecossistema (ADR-016 §5 do SGPM).
 *
 * O navegador vai ao SIGA pelo endereço público (SIGA_URL); a troca do código pelo token e a
 * consulta de quem entrou vão direto à API, pela rede interna (SIGA_OIDC_INTERNO). O sistema confia
 * no SIGA para dizer quem é a pessoa (CPF) e decide, com os papéis daqui, o que ela pode fazer.
 */
class SigaOidc
{
    public const ESCOPOS = 'openid profile email cpf';

    public function configurado(): bool
    {
        return filled(config('services.siga.url'));
    }

    public function retorno(): string
    {
        return rtrim((string) config('app.url'), '/').'/siga/retorno';
    }

    /** Endereço da tela de entrada do SIGA; guarda na sessão o que confere a volta. */
    public function urlEntrada(Request $request): string
    {
        $verificador = Str::random(64);
        $estado = Str::random(40);
        $request->session()->put('siga.pedido', ['verificador' => $verificador, 'estado' => $estado]);

        return $this->emissor().'/auth?'.http_build_query([
            'client_id' => $this->cliente(),
            'redirect_uri' => $this->retorno(),
            'response_type' => 'code',
            'scope' => self::ESCOPOS,
            'code_challenge' => rtrim(strtr(base64_encode(hash('sha256', $verificador, true)), '+/', '-_'), '='),
            'code_challenge_method' => 'S256',
            'state' => $estado,
        ]);
    }

    /**
     * Na volta do SIGA: confere o estado, troca o código pelo token e pergunta quem entrou.
     *
     * @return array{cpf: string, nome: string, email: ?string, id_token: ?string, access_token: string}
     */
    public function concluir(Request $request): array
    {
        $pedido = $request->session()->pull('siga.pedido');
        if ($request->filled('error')) {
            throw new SigaException('A entrada foi cancelada no SIGA.');
        }
        if (! $pedido || ! $request->filled('code') || ! hash_equals((string) $pedido['estado'], (string) $request->query('state'))) {
            throw new SigaException('A volta do SIGA não confere com a entrada iniciada aqui. Entre de novo.');
        }

        try {
            $token = Http::asForm()->acceptJson()->timeout(10)->post($this->interno().'/token', [
                'grant_type' => 'authorization_code',
                'code' => $request->query('code'),
                'redirect_uri' => $this->retorno(),
                'client_id' => $this->cliente(),
                'code_verifier' => $pedido['verificador'],
            ]);
            if (! $token->successful()) {
                throw new SigaException('O SIGA não confirmou a entrada. Entre de novo.');
            }
            $eu = Http::withToken($token->json('access_token'))->acceptJson()->timeout(10)->get($this->interno().'/me');
        } catch (ConnectionException) {
            throw new SigaException('O SIGA não respondeu. Confira se ele está no ar e tente de novo.');
        }
        if (! $eu->successful() || ! preg_match('/^\d{11}$/', (string) $eu->json('cpf'))) {
            throw new SigaException('O SIGA não informou o CPF de quem entrou.');
        }

        return ['cpf' => $eu->json('cpf'), 'nome' => (string) $eu->json('name'), 'email' => $eu->json('email'), 'id_token' => $token->json('id_token'), 'access_token' => (string) $token->json('access_token')];
    }

    /**
     * A sessão do SIGA continua valendo? Sair em qualquer sistema (ou o usuário desativado no SIGA)
     * invalida o token. Nulo quando o SIGA não responde: na dúvida, não derruba ninguém.
     */
    public function sessaoAtiva(string $accessToken): ?bool
    {
        try {
            $eu = Http::withToken($accessToken)->acceptJson()->timeout(5)->get($this->interno().'/me');
        } catch (ConnectionException) {
            return null;
        }
        if ($eu->successful()) {
            return true;
        }

        return in_array($eu->status(), [400, 401, 403], true) ? false : null;
    }

    /** Sair do SIGA (encerra a sessão em todos os sistemas) e voltar ao início deste. */
    public function urlSaida(?string $idToken): string
    {
        return $this->emissor().'/session/end?'.http_build_query(array_filter([
            'client_id' => $this->cliente(),
            'post_logout_redirect_uri' => rtrim((string) config('app.url'), '/').'/',
            'id_token_hint' => $idToken,
        ]));
    }

    private function emissor(): string
    {
        return rtrim((string) config('services.siga.url'), '/').'/oidc';
    }

    private function interno(): string
    {
        return rtrim((string) (config('services.siga.oidc_interno') ?: $this->emissor()), '/');
    }

    private function cliente(): string
    {
        return (string) config('services.siga.cliente');
    }
}
