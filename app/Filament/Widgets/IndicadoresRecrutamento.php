<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Inscricao;
use App\Models\Processo;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

/** Indicadores do backoffice: processos por situação, inscrições, homologação, recursos e candidatos. */
class IndicadoresRecrutamento extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected static ?string $pollingInterval = '120s';

    protected function getStats(): array
    {
        $agora     = now();
        $abertos   = Processo::query()->where('data_abertura', '<=', $agora)->where('data_suspensao_parcial', '>', $agora)->count();
        $andamento = Processo::query()->where('data_suspensao_parcial', '<', $agora)->where('data_resultado', '>', $agora)->count();
        $emBreve   = Processo::query()->where('data_abertura', '>', $agora)->count();

        $porDia = Inscricao::query()->where('created_at', '>=', $agora->copy()->subDays(6)->startOfDay())
            ->selectRaw('date(created_at) as dia, count(*) as total')->groupBy('dia')->pluck('total', 'dia');
        $semana      = collect(range(6, 0))->map(fn (int $d): int => (int) ($porDia[Carbon::today()->subDays($d)->toDateString()] ?? 0));
        $total       = Inscricao::query()->count();
        $homologadas = Inscricao::query()->whereIn('situacao', ['Homologado', 'Aprovado'])->count();
        $recursos    = Inscricao::query()->whereNotNull('recurso')->where('recurso', '!=', '')
            ->where(fn ($q) => $q->whereNull('analise_recurso')->orWhere('analise_recurso', ''))->count();
        $candidatos = User::query()->whereDoesntHave('roles')->count();

        return [
            Stat::make('Processos abertos', $abertos)
                ->description("{$andamento} em andamento · {$emBreve} em breve")
                ->descriptionIcon('heroicon-m-clipboard-document-list')->color('success'),
            Stat::make('Inscrições', number_format($total, 0, ',', '.'))
                ->description($semana->sum() . ' nos últimos 7 dias')->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart($semana->all())->color('primary'),
            Stat::make('Homologadas', number_format($homologadas, 0, ',', '.'))
                ->description($total ? round($homologadas / $total * 100) . '% das inscrições' : 'nenhuma inscrição ainda')
                ->descriptionIcon('heroicon-m-check-badge')->color('info'),
            Stat::make('Recursos sem análise', $recursos)
                ->description($recursos ? 'aguardando a comissão' : 'nenhum pendente')
                ->descriptionIcon('heroicon-m-scale')->color($recursos ? 'warning' : 'gray'),
            Stat::make('Candidatos cadastrados', number_format($candidatos, 0, ',', '.'))
                ->description('contas na área do candidato')->descriptionIcon('heroicon-m-users')->color('gray'),
        ];
    }
}
