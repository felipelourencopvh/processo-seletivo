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
            ->withAggregate('secretaria as secretaria_nome', 'nome')
            ->withCount('cargos')
            ->withSum('cargos as vagas_ampla_total', 'vagas_ampla')
            ->withSum('cargos as vagas_pcd_total', 'vagas_pcd')
            ->withSum('cargos as vagas_ppp_total', 'vagas_ppp')
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
        $vagas = fn () => App\Models\ProcessoCargo::query()
            ->selectRaw('coalesce(sum(coalesce(vagas_ampla, 0) + coalesce(vagas_pcd, 0) + coalesce(vagas_ppp, 0)), 0) as total');

        return [
            'situacoes' => App\Enums\ProcessoStatus::getArray(),
            'processos' => $this->processos(),
            'contagem' => [
                'Todos' => App\Models\Processo::count(),
                'ABERTO' => App\Models\Processo::aberto()->count(),
                'EM_BREVE' => App\Models\Processo::emBreve()->count(),
                'EM_ANDAMENTO' => App\Models\Processo::emAndamento()->count(),
                'ENCERRADO' => App\Models\Processo::encerrado()->count(),
            ],
            'vagasAbertas' => (int) $vagas()->whereIn('processo_id', App\Models\Processo::aberto()->select('id'))->value('total'),
            'vagasAno' => (int) $vagas()->whereIn('processo_id', App\Models\Processo::whereYear('data_abertura', now()->year)->select('id'))->value('total'),
            'inscricoes' => App\Models\Inscricao::count(),
        ];
    }
} ?>

<div>
    {{-- Hero: continua o navy do cabeçalho, com o selo do Recrutamento em órbita --}}
    <section class="sangria relative -mt-8 overflow-hidden bg-[#071c2c] pb-20 pt-16 text-white lg:pb-28 lg:pt-20">
        <div class="brilho-rosa -right-44 -top-72 h-[820px] w-[820px]"></div>
        <div class="brilho-laranja -bottom-96 -left-52 h-[600px] w-[600px]"></div>

        <div class="absolute right-[max(2rem,calc(50vw-560px))] top-12 hidden h-[360px] w-[360px] rounded-full border border-dashed border-white/15 lg:block">
            <div class="absolute inset-[50px] rounded-full border border-white/10"></div>
            <img
                src="{{ asset('marca/recrutamento.svg') }}"
                alt=""
                class="absolute left-1/2 top-1/2 h-32 w-32 -translate-x-1/2 -translate-y-1/2 drop-shadow-[0_20px_40px_rgba(219,39,119,0.45)]"
            />
            @foreach ([
                ['o-briefcase', '<b class="text-white">'.$vagasAbertas.' vagas</b> com inscrição aberta', '-left-20 top-10'],
                ['o-check', 'Comprovante <b class="text-white">por e-mail</b>', '-right-16 top-36'],
                ['o-chat-bubble-left-ellipsis', 'Recurso <b class="text-white">pela internet</b>', '-left-6 bottom-2'],
            ] as [$icone, $texto, $posicao])
                <div class="{{ $posicao }} absolute flex items-center gap-2 whitespace-nowrap rounded-xl border border-white/10 bg-[#0e2a3f] px-3 py-2 text-[12.5px] text-[#dbe6ef] shadow-[0_10px_30px_-10px_#000]">
                    <span class="grid h-[26px] w-[26px] place-items-center rounded-lg bg-rosa-600/15 text-rosa-300">
                        <x-mary-icon :name="$icone" class="h-4 w-4" />
                    </span>
                    <span>{!! $texto !!}</span>
                </div>
            @endforeach
        </div>

        <div class="relative z-10 mx-auto w-full max-w-[1240px] px-4 lg:px-8">
            <span class="inline-flex items-center gap-2 rounded-full border border-rosa-600/30 bg-rosa-600/10 px-3 py-1.5 text-[12.5px] font-semibold text-rosa-300">
                <span class="h-[7px] w-[7px] rounded-full bg-[#f36f21]"></span>
                Processos seletivos e concursos
            </span>
            <h1 class="mt-5 max-w-[640px] text-[36px] font-bold leading-[1.05] lg:text-[54px]">
                Trabalhe na prefeitura.
                <span class="text-rosa-400">Inscreva-se pela internet.</span>
            </h1>
            <p class="mt-4 max-w-[560px] text-base leading-relaxed text-[#b8c7d4] lg:text-lg">
                Edital, vagas, cronograma e resultados num lugar só. Você envia os documentos e acompanha cada etapa pelo celular.
            </p>

            <div class="mt-8 flex max-w-[620px] flex-col gap-2 rounded-2xl bg-white p-2 shadow-[0_20px_50px_-20px_#000] sm:flex-row">
                <label class="flex flex-1 items-center gap-2.5 px-3 text-[#7b8b99]">
                    <x-mary-icon name="o-magnifying-glass" class="h-5 w-5 shrink-0" />
                    <input
                        type="search"
                        wire:model.live.debounce="busca"
                        placeholder="Busque pelo cargo, nome ou número do processo"
                        class="w-full border-0 bg-transparent py-3 text-[15.5px] text-[#0b1f2e] placeholder:text-[#7b8b99] focus:outline-none focus:ring-0"
                    />
                </label>
                <a
                    href="#processos"
                    class="inline-flex items-center justify-center gap-2 rounded-[10px] bg-rosa-600 px-5 py-3 text-[14.5px] font-semibold text-white shadow-[0_8px_24px_-8px_rgba(219,39,119,0.7)] hover:bg-rosa-700"
                >
                    Buscar
                    <x-mary-icon name="o-arrow-right" class="h-4 w-4" />
                </a>
            </div>

            <dl class="mt-10 flex flex-wrap gap-x-10 gap-y-4">
                @foreach ([
                    [$contagem['ABERTO'], $contagem['ABERTO'] === 1 ? 'processo aberto' : 'processos abertos'],
                    [$vagasAno, 'vagas em '.now()->year],
                    [$inscricoes, 'inscrições recebidas'],
                ] as [$numero, $rotulo])
                    <div>
                        <dt class="sr-only">{{ $rotulo }}</dt>
                        <dd class="font-display text-2xl font-bold lg:text-[30px]">{{ number_format($numero, 0, ',', '.') }}</dd>
                        <dd class="text-[13.5px] text-[#9fb2c2]">{{ $rotulo }}</dd>
                    </div>
                @endforeach
            </dl>
        </div>
    </section>

    {{-- Lista de processos --}}
    <section id="processos" class="scroll-mt-6 pt-16">
        <div class="mb-7 flex flex-wrap items-end justify-between gap-6">
            <div>
                <span class="kicker">Processos seletivos</span>
                <h2 class="mt-1.5 text-[26px] font-bold lg:text-[32px]">Escolha um processo</h2>
            </div>
            <div class="flex max-w-full gap-1.5 overflow-x-auto rounded-xl bg-[#e7ecf1] p-1.5" role="tablist">
                @foreach ($situacoes as $opcao)
                    <button
                        type="button"
                        role="tab"
                        wire:click="$set('situacao', '{{ $opcao['id'] }}')"
                        aria-selected="{{ $situacao === $opcao['id'] ? 'true' : 'false' }}"
                        @class([
                            'whitespace-nowrap rounded-[9px] px-3.5 py-2 text-sm font-semibold',
                            'bg-white text-[#0b1f2e] shadow-sm' => $situacao === $opcao['id'],
                            'text-[#4b5d6c] hover:text-[#0b1f2e]' => $situacao !== $opcao['id'],
                        ])
                    >
                        {{ $opcao['id'] === 'Todos' ? 'Todos' : $opcao['name'] }}
                        <small class="ml-1 font-bold text-rosa-600">{{ $contagem[$opcao['id']] ?? 0 }}</small>
                    </button>
                @endforeach
            </div>
        </div>

        <div class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3" wire:loading.class="opacity-60">
            @forelse ($processos as $processo)
                @php
                    $vagasProcesso = (int) $processo->vagas_ampla_total + (int) $processo->vagas_pcd_total + (int) $processo->vagas_ppp_total;
                    $acao = match ($processo->status) {
                        App\Enums\ProcessoStatus::ABERTO => 'Ver e se inscrever',
                        App\Enums\ProcessoStatus::EM_BREVE => 'Ver o edital',
                        App\Enums\ProcessoStatus::EM_ANDAMENTO => 'Acompanhar',
                        App\Enums\ProcessoStatus::ENCERRADO => 'Ver resultados',
                    };
                @endphp

                <a
                    wire:navigate
                    href="{{ route('processos-seletivos.show', $processo) }}"
                    class="caixa group flex flex-col gap-4 p-[22px] transition duration-200 hover:-translate-y-0.5 hover:border-rosa-300 hover:shadow-[0_18px_40px_-22px_rgba(219,39,119,0.55)]"
                >
                    <div class="flex items-center justify-between gap-3">
                        <span class="flex min-w-0 items-center gap-2.5 text-[13px] font-medium text-[#5a6b7b]">
                            <span class="grid h-[34px] w-[34px] shrink-0 place-items-center rounded-[10px] bg-rosa-50 text-rosa-600">
                                <x-mary-icon name="o-building-library" class="h-[18px] w-[18px]" />
                            </span>
                            <span class="truncate">{{ $processo->secretaria_nome }}</span>
                        </span>
                        <span class="situacao situacao-{{ str($processo->status->value)->slug() }}">{{ $processo->status->value }}</span>
                    </div>

                    <h3 class="line-clamp-2 text-lg font-semibold leading-snug">{{ $processo->titulo }}</h3>

                    <div class="grid grid-cols-2 gap-2.5 rounded-xl bg-[#f7f9fb] px-3.5 py-3 text-[12.5px] text-[#6b7c8a]">
                        <div>
                            Vagas
                            <b class="mt-0.5 block text-[15px] font-semibold text-[#0b1f2e]">{{ $vagasProcesso ?: '—' }}</b>
                        </div>
                        <div>
                            Cargos
                            <b class="mt-0.5 block text-[15px] font-semibold text-[#0b1f2e]">{{ $processo->cargos_count }}</b>
                        </div>
                    </div>

                    <div class="flex items-start gap-2 text-[13.5px] text-[#5a6b7b]">
                        <x-mary-icon name="o-calendar" class="mt-0.5 h-4 w-4 shrink-0" />
                        <span>
                            Inscrições de
                            <b class="text-[#0b1f2e]">{{ $processo->data_abertura?->format('d/m/Y') }}</b>
                            a
                            <b class="text-[#0b1f2e]">{{ $processo->data_suspensao_parcial?->format('d/m/Y') }}</b>
                        </span>
                    </div>

                    <div class="mt-auto flex items-center justify-between border-t border-[#eef2f5] pt-3.5 text-[14.5px] font-semibold text-rosa-600">
                        {{ $acao }}
                        <x-mary-icon name="o-arrow-right" class="h-[18px] w-[18px] transition group-hover:translate-x-0.5" />
                    </div>
                </a>
            @empty
                <div class="caixa col-span-full flex flex-col items-center gap-2 px-6 py-14 text-center">
                    <span class="grid h-12 w-12 place-items-center rounded-2xl bg-rosa-50 text-rosa-600">
                        <x-mary-icon name="o-magnifying-glass" class="h-6 w-6" />
                    </span>
                    <b class="mt-2 text-lg">Nenhum processo encontrado</b>
                    <p class="text-sm text-[#5a6b7b]">Tente outra palavra na busca ou escolha outra situação.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $processos->links() }}
        </div>
    </section>

    {{-- Como funciona --}}
    <section id="como-funciona" class="sangria -mb-16 mt-16 scroll-mt-6 border-y border-[#e3e9ef] bg-white py-16">
        <div class="mx-auto w-full max-w-[1240px] px-4 lg:px-8">
            <span class="kicker">Como funciona</span>
            <h2 class="mt-1.5 text-[26px] font-bold lg:text-[32px]">Do cadastro ao resultado, sem papel</h2>

            <div class="mt-7 grid grid-cols-1 gap-[18px] sm:grid-cols-2 lg:grid-cols-4">
                @foreach ([
                    ['Crie sua conta', 'Com CPF e e-mail. Seus dados ficam guardados para os próximos processos.'],
                    ['Escolha o cargo', 'Veja as vagas, a cota (ampla, PcD ou PPP) e os documentos pedidos.'],
                    ['Envie os documentos', 'Foto do celular ou PDF. O comprovante chega no seu e-mail.'],
                    ['Acompanhe', 'Pontuação, classificação e recurso pela sua área, sem ir à prefeitura.'],
                ] as $i => [$titulo, $texto])
                    <div class="rounded-2xl bg-[#f7f9fb] p-[22px]">
                        <i @class([
                            'mb-3.5 grid h-[30px] w-[30px] place-items-center rounded-[9px] font-display text-[13px] font-extrabold not-italic text-white',
                            'bg-rosa-600' => $i === 0,
                            'bg-[#071c2c]' => $i > 0,
                        ])>{{ $i + 1 }}</i>
                        <b class="mb-1.5 block text-base">{{ $titulo }}</b>
                        <p class="text-sm leading-relaxed text-[#5a6b7b]">{{ $texto }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
</div>
