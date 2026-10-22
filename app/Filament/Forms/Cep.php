<?php

declare(strict_types=1);

namespace App\Filament\Forms;

use App\Actions\GetAddressFromCEPAction;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Component;
use Filament\Forms\Set;
use Leandrocfe\FilamentPtbrFormFields\Cep as CepPtbr;
use Livewire\Component as Livewire;

/**
 * CEP que completa o endereço pela base interna do STX Gov (no lugar do ViaCEP do pacote).
 * Só preenche o que a base trouxe: CEP fora da base, ou de cidade inteira, deixa digitar o resto.
 */
class Cep extends CepPtbr
{
    /** @param  array<string, string>  $setFields  campo do formulário => chave do endereço (logradouro, complemento, bairro, localidade, uf) */
    public function baseCep(array $setFields = []): static
    {
        $consultar = function ($state, Livewire $livewire, Set $set, Component $component) use ($setFields): void {
            $livewire->validateOnly($component->getKey());
            $endereco = (new GetAddressFromCEPAction($state))->execute();
            foreach ($setFields as $campo => $chave) {
                if (filled($endereco[$chave] ?? null)) {
                    $set($campo, $endereco[$chave]);
                }
            }
        };

        return $this
            ->minLength(9)
            ->mask('99999-999')
            ->afterStateUpdated($consultar)
            ->suffixAction(
                Action::make('buscar-cep')
                    ->label('Buscar CEP')
                    ->icon('heroicon-o-magnifying-glass')
                    ->action($consultar)
                    ->cancelParentActions(),
            );
    }
}
