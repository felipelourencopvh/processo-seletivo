<?php

declare(strict_types=1);

namespace App\Filament\App\Widgets;

use App\Models\Inscricao;
use App\Models\Processo;
use Filament\Facades\Filament;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

/** Indicadores do processo seletivo aberto no painel da comissão (/app): inscrições, homologação, recursos e vagas. */
class IndicadoresProcesso extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected static ?string $pollingInterval = '120s';

    protected function getStats(): array
    {
        /** @var Processo $processo */
        $processo = Filament::getTenant();
        $inscricoes = Inscricao::query()->where('processo_id', $processo->getKey());

        $porDia = (clone $inscricoes)->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->selectRaw('date(created_at) as dia, count(*) as total')->groupBy('dia')->pluck('total', 'dia');
        $semana = collect(range(6, 0))->map(fn (int $d): int => (int) ($porDia[Carbon::today()->subDays($d)->toDateString()] ?? 0));

        $total       = (clone $inscricoes)->count();
        $homologadas = (clone $inscricoes)->whereIn('situacao', ['Homologado', 'Aprovado'])->count();
        $pendentes   = (clone $inscricoes)->whereIn('situacao', ['Inscrito', 'Pendente'])->count();
        $recursos    = (clone $inscricoes)->whereNotNull('recurso')->where('recurso', '!=', '')
            ->where(fn ($q) => $q->whereNull('analise_recurso')->orWhere('analise_recurso', ''))->count();

        $cargos = $processo->cargos()->get();
        $vagas  = $cargos->sum(fn ($c): int => (int) $c->vagas_ampla + (int) $c->vagas_pcd + (int) $c->vagas_ppp);

        $prazo = match (true) {
            $processo->status->isAberto() => 'inscrições até ' . $processo->data_suspensao_parcial->format('d/m/Y'),
            $processo->data_abertura?->isFuture() ?? false => 'abre em ' . $processo->data_abertura->format('d/m/Y'),
            default => 'inscrições encerradas',
        };

        return [
            Stat::make('Inscrições', number_format($total, 0, ',', '.'))
                ->description($semana->sum() . ' nos últimos 7 dias')->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart($semana->all())->color('primary'),
            Stat::make('Aguardando análise', number_format($pendentes, 0, ',', '.'))
                ->description($pendentes ? 'inscritas ou pendentes' : 'nada na fila')
                ->descriptionIcon('heroicon-m-inbox-stack')->color($pendentes ? 'warning' : 'gray'),
            Stat::make('Homologadas', number_format($homologadas, 0, ',', '.'))
                ->description($total ? round($homologadas / $total * 100) . '% das inscrições' : 'nenhuma inscrição ainda')
                ->descriptionIcon('heroicon-m-check-badge')->color('success'),
            Stat::make('Recursos sem análise', $recursos)
                ->description($recursos ? 'aguardando a comissão' : 'nenhum pendente')
                ->descriptionIcon('heroicon-m-scale')->color($recursos ? 'warning' : 'gray'),
            Stat::make('Vagas', number_format($vagas, 0, ',', '.'))
                ->description($cargos->count() . ($cargos->count() === 1 ? ' cargo · ' : ' cargos · ') . $prazo)
                ->descriptionIcon('heroicon-m-briefcase')->color('info'),
        ];
    }
}
