<?php

use Livewire\Volt\Component;
use Mary\Traits\Toast;

new class () extends Component {
    use Toast;

    public App\Models\Processo $processo;

    public function mount()
    {
        $this->processo->load(['anexos', 'cargos', 'secretaria'])->loadCount('inscricoes');
    }

    public function with()
    {
        $p = $this->processo;

        // Cronograma montado com as datas cadastradas no backoffice; etapa sem data não aparece.
        $etapas = collect([
            ['Inscrições', $p->data_abertura, $p->data_suspensao_parcial],
            ['Homologação das inscrições', $p->data_homologacao, null],
            ['Classificação preliminar', $p->data_liberacao_classificacao, null],
            ['Recursos', $p->data_recurso_inicio, $p->data_recurso_fim],
            ['Resultado final', $p->data_resultado, null],
        ])->filter(fn ($e) => filled($e[1]))->values();

        $atual = $etapas->filter(fn ($e) => $e[1] <= now())->keys()->last();

        return [
            'etapas' => $etapas,
            'atual' => $atual,
            'vagas' => $p->cargos->sum(fn ($c) => (int) $c->vagas_ampla + (int) $c->vagas_pcd + (int) $c->vagas_ppp),
            'grupos' => [
                'editais' => 'Editais e erratas',
                'resultados' => 'Resultados, resoluções e editais de convocação',
                'anexos' => 'Anexos',
            ],
        ];
    }
} ?>

<div>
    @php
        $periodo = fn ($inicio, $fim) => $fim ? $inicio->format('d/m').' a '.$fim->format('d/m/Y') : $inicio->format('d/m/Y');
        $status = $processo->status;
    @endphp

    {{-- Cabeçalho do processo com a trilha de etapas --}}
    <section class="sangria relative -mt-8 overflow-hidden bg-[#071c2c] pt-10 text-white">
        <div class="brilho-rosa -right-52 -top-80 h-[700px] w-[700px]"></div>

        <div class="relative z-10 mx-auto w-full max-w-[1240px] px-4 lg:px-8">
            <nav class="flex gap-2 text-[13px] text-[#9fb2c2]" aria-label="Você está em">
                <a wire:navigate href="{{ route('home') }}" class="hover:text-white">Processos</a>
                <span>›</span>
                <b class="truncate font-medium text-white">{{ $processo->titulo }}</b>
            </nav>

            <h1 class="mt-4 max-w-[820px] text-[28px] font-bold leading-[1.12] lg:text-[40px]">{{ $processo->titulo }}</h1>

            <div class="mt-3 flex flex-wrap items-center gap-x-5 gap-y-2 text-[15px] text-[#b8c7d4]">
                <span class="situacao situacao-{{ str($status->value)->slug() }}">
                    {{ $status->isAberto() ? 'Inscrições abertas' : $status->value }}
                </span>
                @if ($processo->secretaria)
                    <span class="flex items-center gap-1.5">
                        <x-mary-icon name="o-building-library" class="h-4 w-4" />
                        {{ $processo->secretaria->nome }}
                    </span>
                @endif
                <span class="flex items-center gap-1.5">
                    <x-mary-icon name="o-users" class="h-4 w-4" />
                    {{ number_format($processo->inscricoes_count, 0, ',', '.') }} {{ $processo->inscricoes_count === 1 ? 'inscrito' : 'inscritos' }}
                </span>
            </div>

            @if ($etapas->isNotEmpty())
                <ol class="mt-9 grid grid-cols-1 border-white/10 lg:border-t lg:[grid-template-columns:repeat(var(--etapas),minmax(0,1fr))]" style="--etapas: {{ $etapas->count() }}">
                    @foreach ($etapas as $i => [$nome, $inicio, $fim])
                        <li
                            @class([
                                'relative pb-6 pr-4 pt-[18px] text-[13px] text-[#8ea3b4] before:absolute before:inset-x-0 before:-top-px before:h-[3px]',
                                'before:bg-rosa-600' => $i === $atual,
                                'before:bg-white/10 max-lg:hidden' => $i !== $atual,
                            ])
                        >
                            <b @class(['mb-0.5 block text-[14.5px] font-semibold', 'text-white' => $i === $atual, 'text-[#c9d6e2]' => $i !== $atual])>{{ $nome }}</b>
                            {{ $periodo($inicio, $fim) }}
                            @if ($i === $atual)
                                <br />
                                <span class="mt-1.5 inline-block rounded-md bg-rosa-600 px-2 py-0.5 text-[11px] font-bold text-white">Agora</span>
                            @endif
                        </li>
                    @endforeach
                </ol>
            @else
                <div class="h-10"></div>
            @endif
        </div>
    </section>

    <div class="grid items-start gap-7 pt-10 lg:grid-cols-[1fr_360px]">
        {{-- Quadro de inscrição (no celular vem antes do conteúdo) --}}
        <aside class="lg:sticky lg:top-6 lg:order-2">
            <div class="relative overflow-hidden rounded-[20px] bg-[#071c2c] p-[26px] text-white">
                <div class="brilho-rosa -right-28 -top-36 h-[300px] w-[300px]"></div>
                <div class="relative">
                    <div class="text-[13px] font-semibold text-rosa-300">
                        @if ($status->isAberto())
                            @php $dias = (int) ceil(now()->diffInDays($processo->data_suspensao_parcial)); @endphp
                            {{ $dias <= 1 ? 'Inscrições encerram hoje' : "Inscrições encerram em $dias dias" }}
                        @elseif ($status === App\Enums\ProcessoStatus::EM_BREVE)
                            Inscrições abrem em {{ $processo->data_abertura->format('d/m/Y') }}
                        @else
                            Inscrições encerradas
                        @endif
                    </div>
                    <div class="my-1 font-display text-[44px] font-bold">
                        {{ $vagas ?: '—' }}
                        <small class="font-sans text-base font-medium text-[#b8c7d4]">
                            {{ $vagas === 1 ? 'vaga' : 'vagas' }} em {{ $processo->cargos->count() }} {{ $processo->cargos->count() === 1 ? 'cargo' : 'cargos' }}
                        </small>
                    </div>

                    <ul class="my-5 grid gap-2.5 text-sm text-[#c9d6e2]">
                        @if ($processo->data_suspensao_parcial)
                            <li class="flex items-center gap-2.5">
                                <x-mary-icon name="o-check" class="h-4 w-4 text-rosa-400" />
                                Inscrições até {{ $processo->data_suspensao_parcial->format('d/m/Y \à\s H\hi') }}
                            </li>
                        @endif
                        <li class="flex items-center gap-2.5">
                            <x-mary-icon name="o-check" class="h-4 w-4 text-rosa-400" />
                            Tudo pela internet, sem ir à prefeitura
                        </li>
                        <li class="flex items-center gap-2.5">
                            <x-mary-icon name="o-check" class="h-4 w-4 text-rosa-400" />
                            Comprovante por e-mail
                        </li>
                    </ul>

                    @if ($status->isAberto())
                        <a
                            wire:navigate
                            href="{{ route('processos-seletivos.inscrever', $processo) }}"
                            class="flex w-full items-center justify-center gap-2 rounded-[10px] bg-rosa-600 px-4 py-3.5 text-[15.5px] font-semibold text-white shadow-[0_8px_24px_-8px_rgba(219,39,119,0.7)] hover:bg-rosa-700"
                        >
                            Quero me inscrever
                            <x-mary-icon name="o-arrow-right" class="h-[18px] w-[18px]" />
                        </a>
                    @elseif (auth()->check())
                        <a
                            wire:navigate
                            href="{{ route('candidato.index') }}"
                            class="flex w-full items-center justify-center gap-2 rounded-[10px] border border-white/20 px-4 py-3.5 text-[15.5px] font-semibold text-white hover:bg-white/10"
                        >
                            Ver minhas inscrições
                            <x-mary-icon name="o-arrow-right" class="h-[18px] w-[18px]" />
                        </a>
                    @endif
                </div>
            </div>
        </aside>

        <main class="grid min-w-0 gap-[22px] lg:order-1">
            @if (filled(strip_tags((string) $processo->descricao)))
                <section class="caixa p-[26px]">
                    <h2 class="mb-4 flex items-center gap-2.5 text-xl font-bold">
                        <span class="grid h-8 w-8 place-items-center rounded-[9px] bg-rosa-50 text-rosa-600">
                            <x-mary-icon name="o-document-text" class="h-[18px] w-[18px]" />
                        </span>
                        Sobre o processo
                    </h2>
                    <div class="texto-rico text-[15px]">{!! $processo->descricao !!}</div>
                </section>
            @endif

            @if ($processo->cargos->isNotEmpty())
                <section class="caixa p-[26px]">
                    <h2 class="mb-4 flex items-center gap-2.5 text-xl font-bold">
                        <span class="grid h-8 w-8 place-items-center rounded-[9px] bg-rosa-50 text-rosa-600">
                            <x-mary-icon name="o-briefcase" class="h-[18px] w-[18px]" />
                        </span>
                        Cargos e vagas
                    </h2>
                    <table class="w-full border-collapse text-sm">
                        <thead class="max-md:hidden">
                            <tr class="text-left text-xs font-semibold uppercase tracking-wider text-[#7b8b99]">
                                <th class="px-2.5 pb-2.5 font-semibold">Cargo</th>
                                <th class="px-2.5 pb-2.5 font-semibold">Escolaridade</th>
                                <th class="px-2.5 pb-2.5 font-semibold">Vagas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($processo->cargos as $cargo)
                                <tr class="border-t border-[#eef2f5] max-md:grid max-md:grid-cols-[1fr_auto] max-md:gap-y-2 max-md:py-3">
                                    <td class="px-2.5 py-3.5 align-middle max-md:col-span-2 max-md:p-0">
                                        <b class="block font-semibold">{{ $cargo->nome }}</b>
                                        @if ($cargo->localidade || $cargo->descricao)
                                            <small class="text-[12.5px] text-[#7b8b99]">{{ collect([$cargo->localidade, $cargo->descricao])->filter()->join(' · ') }}</small>
                                        @endif
                                    </td>
                                    <td class="px-2.5 py-3.5 align-middle max-md:p-0">
                                        @if ($cargo->nivel)
                                            <span class="whitespace-nowrap rounded-[7px] bg-[#eef2f5] px-2 py-1 text-xs font-semibold text-[#41525f]">
                                                {{ str($cargo->nivel->getLabel())->after('Ensino ') }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-2.5 py-3.5 align-middle max-md:p-0">
                                        <div class="flex flex-wrap gap-1.5 text-xs font-bold">
                                            <span class="rounded-[7px] bg-rosa-50 px-2 py-1 text-rosa-700" title="Ampla concorrência">{{ (int) $cargo->vagas_ampla }} ampla</span>
                                            <span class="rounded-[7px] bg-indigo-50 px-2 py-1 text-indigo-700" title="Pessoa com deficiência">{{ (int) $cargo->vagas_pcd }} PcD</span>
                                            <span class="rounded-[7px] bg-orange-50 px-2 py-1 text-orange-700" title="Pretos, pardos e indígenas">{{ (int) $cargo->vagas_ppp }} PPP</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </section>
            @endif

            <section class="caixa p-[26px]">
                <h2 class="mb-1 flex items-center gap-2.5 text-xl font-bold">
                    <span class="grid h-8 w-8 place-items-center rounded-[9px] bg-rosa-50 text-rosa-600">
                        <x-mary-icon name="o-arrow-down-tray" class="h-[18px] w-[18px]" />
                    </span>
                    Documentos publicados
                </h2>
                @php $porTipo = $processo->anexos->sortByDesc('data_publicacao')->groupBy('tipo'); @endphp
                @foreach ($grupos as $tipo => $rotulo)
                    <h3 class="mb-2 mt-5 font-sans text-[13px] font-bold uppercase tracking-wider text-[#7b8b99]">{{ $rotulo }}</h3>
                    @forelse ($porTipo->get($tipo, collect()) as $anexo)
                        <a
                            href="{{ $anexo->getFirstMediaUrl() }}"
                            target="_blank"
                            rel="noopener"
                            class="mb-2 flex items-center gap-3 rounded-xl border border-[#eef2f5] px-3.5 py-3 hover:border-rosa-300"
                        >
                            <x-mary-icon name="o-document-text" class="h-[18px] w-[18px] shrink-0 text-rosa-600" />
                            <b class="min-w-0 flex-1 text-[14.5px] font-semibold">{{ $anexo->nome }}</b>
                            <small class="whitespace-nowrap text-[12.5px] text-[#7b8b99]">{{ $anexo->data_publicacao?->format('d/m/Y') }}</small>
                            <x-mary-icon name="o-arrow-down-tray" class="h-[17px] w-[17px] shrink-0 text-[#5a6b7b]" />
                        </a>
                    @empty
                        <div class="mb-2 flex items-center gap-2.5 rounded-xl border border-dashed border-[#e3e9ef] px-3.5 py-3 text-[#8b9aa7]">
                            <x-mary-icon name="o-clock" class="h-[17px] w-[17px]" />
                            Ainda não publicado
                        </div>
                    @endforelse
                @endforeach
            </section>
        </main>
    </div>
</div>
