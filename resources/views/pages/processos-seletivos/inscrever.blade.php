<?php

use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use Mary\Traits\Toast;

new class () extends Component {
    use Toast;
    use App\Traits\ClearsProperties;

    public App\Models\Processo       $processo;
    public bool                      $habilitado = true;
    public bool                      $possuiInscricao = false;
    public ?App\Models\ProcessoCargo $cargo;

    #[Livewire\Attributes\Url]
    public ?string $cota = '';

    #[Livewire\Attributes\Url]
    public ?string $localidade = '';

    #[Livewire\Attributes\Url]
    public array $sortBy = ['column' => 'nome', 'direction' => 'asc'];

    #[On('cargo-saved')]
    #[On('cargo-cancel')]
    public function clear(): void
    {
        $this->cargo = null;
    }

    public function mount()
    {
        if (auth()->user()->inscricoes()->where('processo_id', $this->processo->id)->exists()) {
            $this->habilitado = false;
            $this->possuiInscricao = true;
        }

        if ( ! $this->processo->status->isAberto()) {
            $this->habilitado = false;
        }

        $this->processo->load(['cargos', 'secretaria']);
    }

    public function save3()
    {
        $this->warning(
            'Erro',
            'Já possui uma inscrição ou o Processo não está ativo',
            redirectTo: '/',
        );
    }

    public function selecionaCargo(App\Models\ProcessoCargo $cargo): void
    {
        $this->cargo = $cargo;
    }

    public function headers(): array
    {
        return [
            ['key' => 'nome', 'label' => 'Cargo'],
            ['key' => 'nivel', 'label' => 'Nível de Ensino'],
            ['key' => 'localidade', 'label' => 'Localidade'],
            ['key' => 'vagas_ampla', 'label' => 'Vagas AC*'],
            ['key' => 'vagas_pcd', 'label' => 'Vagas PcD*'],
            ['key' => 'vagas_ppp', 'label' => 'Vagas PPP*'],
        ];
    }

    public function cargos()
    {
        return App\Models\ProcessoCargo::query()
            ->where('processo_id', $this->processo->id)
            ->when($this->cota === 'ampla', fn (Builder $q) => $q->where('vagas_ampla', '>', 0))
            ->when($this->cota === 'ppp', fn (Builder $q) => $q->where('vagas_ppp', '>', 0))
            ->when($this->cota === 'pcd', fn (Builder $q) => $q->where('vagas_pcd', '>', 0))
            ->when($this->localidade, fn (Builder $q) => $q->where('localidade', $this->localidade))
            ->orderBy(...array_values($this->sortBy))
            ->get();
    }

    public function with(): array
    {
        return [
            'headers' => $this->headers(),
            'cargos' => $this->cargos(),
        ];
    }
} ?>

<div>
    @if ($habilitado)
        @php
            $cotas = [
                ['id' => 'ampla', 'name' => 'Ampla Concorrência'],
                ['id' => 'pcd', 'name' => 'Pessoa com Deficiência'],
                ['id' => 'ppp', 'name' => 'Pessoa Preta ou Parda'],
            ];

            $localidades = App\Models\ProcessoCargo::query()->where('processo_id', $this->processo->id)->groupBy('localidade')->pluckWithIdName('localidade', 'localidade');
        @endphp

        <x-card title="Inscrever-se:" subtitle="{{ $processo->titulo }}">
            <x-form wire:submit="save">
                <x-select
                    label="Selecione uma Cota"
                    icon="o-user"
                    :options="$cotas"
                    wire:model.live="cota"
                    placeholder="Selecione uma cota"
                    placeholder-value="0"
                />
                <x-select
                    label="Selecione uma Localidade"
                    icon="o-user"
                    :options="$localidades"
                    wire:model.live="localidade"
                    placeholder="Selecione uma Localidade"
                    placeholder-value="0"
                />

                <x-table :headers="$headers" :rows="$cargos" :sort-by="$sortBy">
                    @scope('cell_nivel', $cargo)
                        {{ $cargo->nivel->getLabel() }}
                    @endscope

                    @scope('actions', $cargo)
                        <div class="flex items-center space-x-2">
                            <x-button
                                label="Selecionar"
                                class="btn-primary btn-xs"
                                wire:click="selecionaCargo({{ $cargo->id }})"
                                spinner
                            />
                        </div>
                    @endscope
                </x-table>
            </x-form>
        </x-card>
    @else
        @if ($possuiInscricao)
            <x-card title="Processo Seletivo:" subtitle="{{ $processo->titulo }}">
                <x-button
                    label="Já possui inscrição para esse Processo Seletivo, clique aqui para ver suas inscrições"
                    class="btn-error"
                    :link="route('candidato.index')"
                    spinner
                />
            </x-card>
        @else
            <x-card title="Processo Seletivo:" subtitle="{{ $processo->titulo }}">
                <x-button label="Inscrições encerradas" class="btn-error" wire:click="save3" spinner />
            </x-card>
        @endif
    @endif
    <livewire:processos-seletivos.selecionar-cargo wire:model="cargo" />
</div>
