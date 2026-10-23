<?php

declare(strict_types=1);

namespace App\Filament\App\Widgets;

use App\Models\Inscricao;
use App\Models\Processo;
use Filament\Facades\Filament;
use Filament\Widgets\ChartWidget;

/** Inscrições por cargo do processo aberto no painel, lado a lado com as vagas oferecidas. */
class InscricoesPorCargo extends ChartWidget
{
    protected static ?string $heading = 'Inscrições e vagas por cargo';

    protected static ?int $sort = 3;

    protected static ?string $maxHeight = '260px';

    protected function getData(): array
    {
        /** @var Processo $processo */
        $processo = Filament::getTenant();
        $cargos = $processo->cargos()->orderBy('nome')->get();
        $porCargo = Inscricao::query()->where('processo_id', $processo->getKey())
            ->selectRaw('cargo_id, count(*) as total')->groupBy('cargo_id')->pluck('total', 'cargo_id');

        return [
            'datasets' => [
                [
                    'label'           => 'Inscrições',
                    'data'            => $cargos->map(fn ($c): int => (int) ($porCargo[$c->id] ?? 0))->all(),
                    'backgroundColor' => '#db2777',
                    'borderRadius'    => 6,
                ],
                [
                    'label'           => 'Vagas',
                    'data'            => $cargos->map(fn ($c): int => (int) $c->vagas_ampla + (int) $c->vagas_pcd + (int) $c->vagas_ppp)->all(),
                    'backgroundColor' => '#071c2c',
                    'borderRadius'    => 6,
                ],
            ],
            'labels' => $cargos->pluck('nome')->all(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'indexAxis' => 'y',
            'plugins'   => ['legend' => ['position' => 'bottom']],
            'scales'    => ['x' => ['beginAtZero' => true, 'ticks' => ['precision' => 0]]],
        ];
    }
}
