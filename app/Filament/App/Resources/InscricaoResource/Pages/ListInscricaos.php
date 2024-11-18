<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\InscricaoResource\Pages;

use App\Filament\App\Resources\InscricaoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

class ListInscricaos extends ListRecords
{
    protected static string $resource = InscricaoResource::class;

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Todos'),
            'Pendente' => Tab::make('Pendente')
                ->modifyQueryUsing(function ($query) {
                    $query->where('situacao', 'Pendente');
                })->icon('heroicon-o-clock'),
            'Recursos' => Tab::make('Recursos')
                ->modifyQueryUsing(function ($query) {
                    $query->whereNotNull('recurso');
                })->icon('heroicon-o-clock'),
            'Homologado' => Tab::make('Homologado')
                ->modifyQueryUsing(function ($query) {
                    $query->where('situacao', 'Homologado');
                })
                ->icon('heroicon-o-check-circle'),
            'Reprovado' => Tab::make('Reprovado')
                ->modifyQueryUsing(function ($query) {
                    $query->where('situacao', 'Reprovado');
                })->icon('heroicon-o-x-circle'),
            'Aprovado' => Tab::make('Aprovado')
                ->modifyQueryUsing(function ($query) {
                    $query->where('situacao', 'Aprovado');
                })->icon('heroicon-o-check-badge'),

        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
