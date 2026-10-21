<?php

declare(strict_types=1);

use App\Filament\Widgets\IndicadoresRecrutamento;
use App\Filament\Widgets\InscricoesPorDia;
use App\Filament\Widgets\ProcessosEmCurso;
use App\Models\Inscricao;
use App\Models\Processo;
use App\Models\ProcessoCargo;
use App\Models\Secretaria;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

it('mostra os indicadores, o gráfico e os processos no painel', function (): void {
    Role::findOrCreate('super_admin', 'web');
    $admin = User::factory()->create();
    $admin->assignRole('super_admin');
    $secretaria = Secretaria::query()->create(['nome' => 'Secretaria de Saúde', 'sigla' => 'SMS']);
    $processo   = Processo::query()->forceCreate(['titulo' => 'Seletivo Saúde 2026', 'slug' => 'seletivo-saude-2026', 'secretaria_id' => $secretaria->id,
        'data_abertura'                                  => now()->subDays(2), 'data_suspensao_parcial' => now()->addDays(10), 'data_resultado' => now()->addDays(30)]);
    $candidato = User::factory()->create();
    $cargo     = ProcessoCargo::query()->forceCreate(['nome' => 'Enfermeiro', 'processo_id' => $processo->id]);
    Inscricao::query()->forceCreate(['processo_id' => $processo->id, 'cargo_id' => $cargo->id, 'user_id' => $candidato->id, 'situacao' => 'Homologado', 'recurso' => 'Peço revisão']);
    $this->actingAs($admin);

    Livewire::test(IndicadoresRecrutamento::class)->assertSee('Processos abertos')->assertSee('Recursos sem análise')->assertSee('100% das inscrições');
    Livewire::test(InscricoesPorDia::class)->assertSee('Inscrições por dia');
    Livewire::test(ProcessosEmCurso::class)->assertSee('Seletivo Saúde 2026')->assertSee('Aberto');
    $this->withoutVite()->get('/admin')->assertOk()->assertDontSee('filamentphp.com');
});
