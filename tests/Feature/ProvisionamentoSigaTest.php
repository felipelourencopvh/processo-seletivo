<?php

declare(strict_types=1);

use App\Models\Processo;
use App\Models\Secretaria;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    config(['services.siga.provisionamento_token' => 'segredo-de-teste']);
});

function siga(): array
{
    return ['Authorization' => 'Bearer segredo-de-teste', 'Accept' => 'application/json'];
}

it('só atende o SIGA com o token e não existe sem token configurado', function (): void {
    $this->getJson('/siga/api/perfis')->assertUnauthorized();
    $this->getJson('/siga/api/perfis', siga())->assertOk()
        ->assertJsonPath('0.codigo', 'administrador')->assertJsonPath('1.exigeAlcance', true);
    config(['services.siga.provisionamento_token' => '']);
    $this->getJson('/siga/api/perfis', siga())->assertNotFound();
});

it('lista os processos seletivos como alcance e grava os perfis pedidos', function (): void {
    $secretaria = Secretaria::query()->create(['nome' => 'Secretaria de Saúde', 'sigla' => 'SMS']);
    $processo   = Processo::query()->forceCreate(['titulo' => 'Seletivo Saúde 2026', 'slug' => 'seletivo-saude-2026', 'secretaria_id' => $secretaria->id]);
    $this->getJson('/siga/api/alcances', siga())->assertOk()->assertJsonPath('0.nome', 'Seletivo Saúde 2026');

    $this->putJson('/siga/api/usuarios/52998224725', ['nome' => 'Ana', 'email' => 'ana@prefeitura.gov.br', 'ativo' => true,
        'acessos'                                            => [['perfil' => 'gestor', 'alcance' => null]]], siga())->assertStatus(422);

    $this->putJson('/siga/api/usuarios/52998224725', ['nome' => 'Ana', 'email' => 'ana@prefeitura.gov.br', 'ativo' => true,
        'acessos'                                            => [['perfil' => 'gestor', 'alcance' => (string) $processo->id]]], siga())
        ->assertOk()->assertJsonPath('existe', true)->assertJsonPath('acessos.0.alcance', (string) $processo->id);
    $ana = User::query()->where('cpf', '52998224725')->sole();
    expect($ana->hasRole('admin'))->toBeTrue()->and($ana->canAccessTenant($processo))->toBeTrue();

    // Vira administrador: o papel de gestor e os processos saem.
    $this->putJson('/siga/api/usuarios/52998224725', ['nome' => 'Ana', 'email' => 'ana@prefeitura.gov.br', 'ativo' => true,
        'acessos'                                            => [['perfil' => 'administrador']]], siga())->assertOk()->assertJsonPath('acessos', [['perfil' => 'administrador', 'alcance' => null, 'de' => null, 'ate' => null]]);
    expect($ana->fresh()->hasRole('super_admin'))->toBeTrue()->and($ana->fresh()->hasRole('admin'))->toBeFalse();

    // Sem acessos: continua usuário (pode ser candidato), sem papel no backoffice.
    $this->putJson('/siga/api/usuarios/52998224725', ['nome' => 'Ana', 'email' => 'ana@prefeitura.gov.br', 'ativo' => true, 'acessos' => []], siga())
        ->assertOk()->assertJsonPath('acessos', []);
    $this->getJson('/siga/api/usuarios/11144477735', siga())->assertOk()->assertJsonPath('existe', false);
});
