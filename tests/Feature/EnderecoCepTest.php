<?php

declare(strict_types=1);

use App\Actions\GetAddressFromCEPAction;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

beforeEach(function (): void {
    config(['services.sgpm.base_url' => 'http://sgpm.test/api/v1', 'services.sgpm.token' => 'chave', 'cache.default' => 'array']);
    Cache::flush();
});

function enderecoSgpm(array $extras = []): array
{
    return [
        'cep'    => '76801012', 'cepFormatado' => '76801-012', 'uf' => 'RO', 'municipio' => 'Porto Velho', 'localidade' => 'Porto Velho',
        'bairro' => 'Centro', 'logradouro' => 'Avenida Carlos Gomes', 'complemento' => null, 'logradouroLivre' => false, ...$extras,
    ];
}

it('busca o CEP na base interna do SGPM, não no ViaCEP', function (): void {
    Http::fake(['sgpm.test/*' => Http::response(enderecoSgpm())]);

    expect((new GetAddressFromCEPAction('76801-012'))->execute())->toMatchArray([
        'logradouro' => 'Avenida Carlos Gomes', 'bairro' => 'Centro', 'localidade' => 'Porto Velho', 'uf' => 'RO',
    ]);
    Http::assertSent(fn ($r) => $r->url() === 'http://sgpm.test/api/v1/integracao/cep/76801012' && $r->hasHeader('Authorization', 'Bearer chave'));
    Http::assertNotSent(fn ($r) => str_contains($r->url(), 'viacep'));
});

it('CEP de cidade inteira não traz rua nem bairro', function (): void {
    Http::fake(['sgpm.test/*' => Http::response(enderecoSgpm(['logradouroLivre' => true, 'logradouro' => null, 'bairro' => null]))]);

    expect((new GetAddressFromCEPAction('76801000'))->execute())->toMatchArray(['logradouro' => null, 'bairro' => null, 'localidade' => 'Porto Velho']);
});

it('fora da base, sem chave ou com o SGPM fora do ar devolve nada e o candidato digita', function (): void {
    Http::fake(['sgpm.test/*' => Http::response(['detail' => 'não achado'], 404)]);
    expect((new GetAddressFromCEPAction('99999999'))->execute())->toBeNull();

    Http::fake(['sgpm.test/*' => fn () => throw new ConnectionException('fora')]);
    expect((new GetAddressFromCEPAction('11111111'))->execute())->toBeNull();

    config(['services.sgpm.token' => null]);
    expect((new GetAddressFromCEPAction('76801012'))->execute())->toBeNull();
    expect((new GetAddressFromCEPAction('123'))->execute())->toBeNull();
});
