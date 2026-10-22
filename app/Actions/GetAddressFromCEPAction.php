<?php

declare(strict_types=1);

namespace App\Actions;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

/**
 * Endereço pelo CEP na base interna do STX Gov (CNEFE do IBGE, consultada pelo SGPM; nada sai para fora).
 * Devolve as chaves no formato que as telas já usavam (logradouro, complemento, bairro, localidade, uf) ou null:
 * CEP fora da base, integração desligada ou SGPM fora do ar, e o candidato digita o endereço.
 * CEP de cidade inteira vem sem logradouro e sem bairro (null): o que foi digitado fica.
 */
readonly class GetAddressFromCEPAction
{
    public function __construct(
        private ?string $cep,
    ) {
    }

    /** @return array{cep: string, logradouro: ?string, complemento: ?string, bairro: ?string, localidade: string, uf: string}|null */
    public function execute(): ?array
    {
        $cep = preg_replace('/\D/', '', (string) $this->cep);
        if (strlen($cep) !== 8 || blank(config('services.sgpm.token'))) {
            return null;
        }

        // A base muda pouco: um dia de cache poupa o SGPM sem prender um endereço velho.
        return Cache::remember("cep:{$cep}", now()->addDay(), function () use ($cep): ?array {
            try {
                $resposta = Http::baseUrl(config('services.sgpm.base_url'))
                    ->withToken(config('services.sgpm.token'))
                    ->acceptJson()
                    ->timeout(config('services.sgpm.timeout'))
                    ->get('/integracao/cep/' . $cep);
            } catch (ConnectionException) {
                return null;
            }
            if ( ! $resposta->successful()) {
                return null;
            }
            $e     = $resposta->json();
            $livre = (bool) ($e['logradouroLivre'] ?? false);

            return [
                'cep'         => $e['cepFormatado'] ?? $cep,
                'logradouro'  => $livre ? null : ($e['logradouro'] ?? null),
                'complemento' => $livre ? null : ($e['complemento'] ?? null),
                'bairro'      => $livre ? null : ($e['bairro'] ?? null),
                'localidade'  => $e['municipio'],
                'uf'          => $e['uf'],
            ];
        });
    }
}
