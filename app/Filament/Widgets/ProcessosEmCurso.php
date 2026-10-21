<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Enums\ProcessoStatus;
use App\Models\Processo;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

/** Os processos seletivos com a situação, as inscrições e os recursos pendentes; o link abre o painel do processo (/app). */
class ProcessosEmCurso extends TableWidget
{
    protected static ?string $heading = 'Processos seletivos';

    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(Processo::query()->with('secretaria')->withCount([
                'inscricoes',
                'inscricoes as recursos_pendentes_count' => fn ($q) => $q->whereNotNull('recurso')->where('recurso', '!=', '')
                    ->where(fn ($q) => $q->whereNull('analise_recurso')->orWhere('analise_recurso', '')),
            ])->latest('data_abertura'))
            ->columns([
                Tables\Columns\TextColumn::make('titulo')->label('Processo')->searchable()->weight('bold')
                    ->description(fn (Processo $p): ?string => $p->secretaria?->sigla),
                Tables\Columns\TextColumn::make('status')->label('Situação')->badge()
                    ->formatStateUsing(fn (ProcessoStatus $state): string => $state->value)
                    ->color(fn (ProcessoStatus $state): string => match ($state) {
                        ProcessoStatus::ABERTO => 'success', ProcessoStatus::EM_ANDAMENTO => 'warning', ProcessoStatus::EM_BREVE => 'info', ProcessoStatus::ENCERRADO => 'gray',
                    }),
                Tables\Columns\TextColumn::make('data_abertura')->label('Abertura')->dateTime('d/m/Y'),
                Tables\Columns\TextColumn::make('data_suspensao_parcial')->label('Inscrições até')->dateTime('d/m/Y'),
                Tables\Columns\TextColumn::make('data_resultado')->label('Resultado')->date('d/m/Y'),
                Tables\Columns\TextColumn::make('inscricoes_count')->label('Inscrições')->numeric()->alignEnd(),
                Tables\Columns\TextColumn::make('recursos_pendentes_count')->label('Recursos pendentes')->numeric()->alignEnd()
                    ->color(fn (int $state): string => $state ? 'warning' : 'gray'),
            ])
            ->recordUrl(fn (Processo $p): string => url("/app/{$p->slug}"))
            ->paginated([5, 10, 25])
            ->defaultPaginationPageOption(5)
            ->emptyStateHeading('Nenhum processo seletivo ainda');
    }
}
