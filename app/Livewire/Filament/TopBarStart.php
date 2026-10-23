<?php

declare(strict_types=1);

namespace App\Livewire\Filament;

use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ManageRecords;
use Livewire\Component;

class TopBarStart extends Component
{
    public ?string $title = null;
    public ?string $icon = null;

    public function mount()
    {
        $currentPage = call_user_func(request()->getRouteResolver())->controller ?? null;

        if ( ! $currentPage) {
            return;
        }

        $resource = method_exists($currentPage, 'getResource') ? app($currentPage::getResource()) : null;

        $this->icon = $currentPage->getNavigationIcon() ?: $resource?->getNavigationIcon();

        // Listagem: o nome do menu ("Cargos"); criar/editar/ver e demais páginas: o título traduzido ("Editar Cargo").
        $this->title = $resource && ($currentPage instanceof ListRecords || $currentPage instanceof ManageRecords)
            ? $resource->getNavigationLabel()
            : (method_exists($currentPage, 'getTitle') ? (string) $currentPage->getTitle() : $currentPage->getNavigationLabel());
    }

    public function render()
    {
        return view('filament.topbar-start');
    }
}
