<?php

declare(strict_types=1);

namespace App\Filament\Exports;

use App\Models\Inscricao;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Str;

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
            ExportColumn::make('documento_mascara')->label('CPF (com Máscara)')->state(function (Inscricao $record): string {
                if (empty($record->user?->info?->documento)) {
                    return '';
                }

                return Str::cpfCnpjMask($record->user->info->documento);
            }),
            ExportColumn::make('user.info.data_nascimento')->label('Data de Nascimento')->formatStateUsing(function (?string $state): string {
                if (empty($state)) {
                    return '';
                }

                return date('d/m/Y', strtotime($state));
            }),
            ExportColumn::make('data_nascimento_mascara')->label('Data de Nascimento (com Máscara)')->state(function (Inscricao $record): string {
                if (empty($record->user?->info?->data_nascimento)) {
                    return '';
                }

                return  '**/**/'.date('Y', strtotime($record->user->info->data_nascimento));
            }),
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
            ExportColumn::make('pontuacao')->label('Pontuação'),
            ExportColumn::make('pontuacao_previa')->label('Pontuação Prévia'),
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
