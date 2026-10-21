<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Services\Siga\SigaOidc;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Quem entrou pelo SIGA sai junto com o SIGA. A cada minuto (no máximo), confere com o SIGA se a
 * sessão de entrada ainda vale; se a pessoa saiu em outro sistema, ou foi desativada no SIGA, a
 * sessão daqui acaba também. Login local (sem token do SIGA, como o candidato) não é afetado.
 */
class ConferirSessaoSiga
{
    public const INTERVALO_SEGUNDOS = 60;

    public function __construct(private readonly SigaOidc $siga) {}

    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->hasSession() ? $request->session()->get('siga.access_token') : null;
        if (! $token || ! Auth::check()) {
            return $next($request);
        }

        $conferido = (int) $request->session()->get('siga.conferido_em', 0);
        if (now()->timestamp - $conferido >= self::INTERVALO_SEGUNDOS) {
            if ($this->siga->sessaoAtiva($token) === false) {
                Auth::guard('web')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return $request->expectsJson()
                    ? response()->json(['message' => 'A sua sessão no SIGA terminou. Entre de novo.'], 401)
                    : redirect('/');
            }
            $request->session()->put('siga.conferido_em', now()->timestamp);
        }

        return $next($request);
    }
}
