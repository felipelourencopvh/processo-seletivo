<?php

use App\Traits\ResetsPaginationWhenPropsChanges;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Volt\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

new class () extends Component {
    use Toast;
    use WithPagination;
    use ResetsPaginationWhenPropsChanges;

    #[Livewire\Attributes\Url]
    public string $situacao = 'Todos';

    #[Livewire\Attributes\Url]
    public array $sortBy = ['column' => 'data_abertura', 'direction' => 'desc'];

    #[Livewire\Attributes\Url]
    public string $busca = '';

    public function mount()
    {
        $this->filtros = App\Enums\ProcessoStatus::getArray();
    }

    public function processos(): LengthAwarePaginator
    {
        return App\Models\Processo::query()
            ->withAggregate('secretaria as secretaria_nome', 'sigla')
            ->when($this->situacao === 'ABERTO', fn (Builder $q) => $q->aberto())
            ->when($this->situacao === 'EM_BREVE', fn (Builder $q) => $q->emBreve())
            ->when($this->situacao === 'EM_ANDAMENTO', fn (Builder $q) => $q->emAndamento())
            ->when($this->situacao === 'ENCERRADO', fn (Builder $q) => $q->encerrado())
            ->when($this->busca, fn (Builder $q) => $q->whereAny(['titulo', 'slug', 'descricao'], 'like', "%$this->busca%"))
            ->orderBy(...array_values($this->sortBy))
            ->paginate(9);
    }

    public function with()
    {
        return [
            'situacoes' => App\Enums\ProcessoStatus::getArray(),
            'processos' => $this->processos(),
        ];
    }
} ?>

<div>
    <x-header title="Processos Seletivos" progress-indicator separator>
        {{-- SEARCH --}}
        <x-slot:middle class="!justify-end">
            <x-input placeholder="Pesquisar..." wire:model.live.debounce="busca" icon="o-magnifying-glass" clearable />
        </x-slot>

        <x-slot:actions>
            <x-radio :options="$situacoes" wire:model.live="situacao" />
        </x-slot>
    </x-header>

    <div class="my-4 font-[sans-serif]">
        <div class="mx-auto">
            <div
                class="mx-auto mt-16 grid grid-cols-1 gap-6 max-lg:max-w-3xl max-md:max-w-md md:grid-cols-2 lg:grid-cols-3"
            >
                @forelse ($processos as $processo)
                    <a
                        wire:navigate
                        class="relative top-0 cursor-pointer overflow-hidden rounded bg-white shadow-[0_2px_10px_-3px_rgba(6,81,237,0.3)] transition-all duration-300 hover:-top-2"
                        href="{{ route('processos-seletivos.show', $processo) }}"
                    >
                        <div class="card image-full h-60 w-full bg-base-100">
                            <figure class="bg-white/30 p-5 backdrop-blur-sm">
                                <img src="{{ asset('media/brasao-pmpv.svg') }}" alt="brasao" />
                            </figure>
                            <div class="card-body">
                                <div class="badge-{{ $processo->status->getColor() }} badge">
                                    {{ $processo->status }}!
                                </div>
                                <h2 class="text-grey-400 text-cent card-title bg-white/30 p-5 backdrop-blur-sm">
                                    {{ $processo->titulo }}!
                                </h2>
                            </div>
                        </div>
                        <div class="p-6">
                            <span class="mb-2 block text-sm text-gray-400">
                                {{ $processo->secretaria_nome }}
                            </span>
                            <span class="mb-2 block text-sm text-gray-400"></span>
                            <h3 class="text-xl font-bold text-gray-800">{{ $processo->titulo }}</h3>
                            <hr class="my-4" />
                            <p>Início das Inscrições: {{ $processo->data_abertura->format('d/m/Y H:i') }}</p>
                            <p>
                                Encerramento das Inscrições:
                                {{ $processo->data_suspensao_parcial->format('d/m/Y H:i') }}
                            </p>
                        </div>
                    </a>
                @empty
                    <x-alert
                        title="Nenhum registro encontrado."
                        icon="o-exclamation-triangle"
                        shadow
                        class="col-span-12"
                    />
                @endforelse
            </div>
        </div>
    </div>

    <x-mary-pagination :rows="$processos" />
</div>
