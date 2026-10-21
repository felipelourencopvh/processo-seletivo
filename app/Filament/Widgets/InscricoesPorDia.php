<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Inscricao;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

/** Inscrições por dia nos últimos 30 dias. */
class InscricoesPorDia extends ChartWidget
{
    protected static ?string $heading = 'Inscrições por dia (30 dias)';

    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $maxHeight = '260px';

    protected function getData(): array
    {
        $porDia = Inscricao::query()->where('created_at', '>=', now()->subDays(29)->startOfDay())
            ->selectRaw('date(created_at) as dia, count(*) as total')->groupBy('dia')->pluck('total', 'dia');
        $dias = collect(range(29, 0))->map(fn (int $d): Carbon => Carbon::today()->subDays($d));

        return [
            'datasets' => [[
                'label'           => 'Inscrições',
                'data'            => $dias->map(fn (Carbon $d): int => (int) ($porDia[$d->toDateString()] ?? 0))->all(),
                'borderColor'     => '#4338ca',
                'backgroundColor' => 'rgba(67, 56, 202, 0.15)',
                'fill'            => true,
                'tension'         => 0.3,
            ]],
            'labels' => $dias->map(fn (Carbon $d): string => $d->format('d/m'))->all(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return ['plugins' => ['legend' => ['display' => false]], 'scales' => ['y' => ['beginAtZero' => true, 'ticks' => ['precision' => 0]]]];
    }
}
