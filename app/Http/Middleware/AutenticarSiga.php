<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * ADR-016 §5: a API de provisionamento só atende o SIGA, que se apresenta com o token gerado para este sistema
 * (SIGA_PROVISIONAMENTO_TOKEN). Sem token configurado, a API não existe (404).
 */
class AutenticarSiga
{
    public function handle(Request $request, Closure $next): Response
    {
        $esperado = (string) config('services.siga.provisionamento_token');
        abort_if($esperado === '', 404);

        if ( ! hash_equals($esperado, (string) $request->bearerToken())) {
            return response()->json(['detail' => 'Token do SIGA inválido.'], 401);
        }

        return $next($request);
    }
}
