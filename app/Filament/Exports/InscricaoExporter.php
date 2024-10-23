<?php

declare(strict_types=1);

namespace App\Filament\Exports;

use App\Models\Inscricao;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class InscricaoExporter extends Exporter
{
    protected static ?string $model = Inscricao::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('user.name')->label('Nome'),
            ExportColumn::make('user.email')->label('E-Mail'),
            ExportColumn::make('user.info.documento')->label('CPF'),
            ExportColumn::make('user.info.data_nascimento')->label('Data de Nascimento')->formatStateUsing(fn (string $state): string => date('d/m/Y', strtotime($state))),
            ExportColumn::make('user.info.sexo')->label('Sexo'),
            ExportColumn::make('user.info.rg')->label('RG'),
            ExportColumn::make('user.info.filiacao_mae')->label('Filiação (Mãe)'),
            ExportColumn::make('user.info.filiacao_pai')->label('Filiação (Pai)'),
            ExportColumn::make('user.info.telefone_celular')->label('Telefone Celular'),
            ExportColumn::make('user.info.telefone_residencial')->label('Telefone Residencial'),
            ExportColumn::make('situacao')->label('Situação'),
            ExportColumn::make('cargo.nome')->label('Cargo'),
            ExportColumn::make('cargo.localidade')->label('Localidade'),
            ExportColumn::make('cota'),
            ExportColumn::make('pontuacao'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Sua exportação de [Inscrições] foi concluída: ' . number_format($export->successful_rows) . ' registro(s) exportado(s) com sucesso.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
