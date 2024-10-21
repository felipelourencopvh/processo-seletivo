<?php

use Livewire\Attributes\Modelable;
use Livewire\Attributes\Rule;
use Livewire\Volt\Component;
use Mary\Traits\Toast;

new class () extends Component {
    use Toast;

    #[Modelable]
    public ?App\Models\ProcessoCargo $cargo = null;

    public bool $show = false;

    #[Rule('required')]
    public string $cota = '';

    public function boot(): void
    {
        if ( ! $this->cargo) {
            $this->show = false;

            return;
        }

        $this->fill($this->cargo);
        $this->show = true;
    }

    public function with(): array
    {
        return [
            'cotas' => $this->cotas(),
        ];
    }

    public function cotas()
    {
        if ($this->cargo) {
            return [
                ['id' => 'ampla', 'name' => 'Ampla Concorrência', 'disabled' => ($this->cargo->vagas_ampla <= 0)],
                ['id' => 'pcd', 'name' => 'Pessoa com Deficiência', 'disabled' => ($this->cargo->vagas_pcd <= 0)],
                ['id' => 'ppp', 'name' => 'Pessoa Preta ou Parda', 'disabled' => ($this->cargo->vagas_ppp <= 0)],
            ];
        }

        return [];
    }

    public function save(App\Actions\CreateNewInscricaoAction $action): void
    {
        $this->validate();

        $inscricao = $action->handle(auth()->user(), $this->cargo, $this->cota);

        $this->success('Inscrição foi enviada com sucesso', redirectTo: '/candidato/inscricoes/'.$inscricao->id);
    }
}; ?>

<div>
    <x-modal wire:model="show" title="Finalizar Inscrição" persistent>
        <x-hr class="mb-5" />
        <x-form wire:submit="save">
            <x-input label="Nome" value="{{ auth()->user()->name }}" readonly />
            <x-input label="Cargo" value="{{ $cargo?->nome }}" readonly />
            <x-input label="Nível" value="{{ $cargo?->nivel->getLabel() }}" readonly />
            <x-input label="Localidade" value="{{ $cargo?->localidade }}" readonly />
            <x-radio label="Selecione uma Cota" :options="$cotas" wire:model="cota" />

            <x-slot:actions>
                <x-button label="Cancelar" @click="$dispatch('cargo-cancel')" />
                <x-button
                    label="Inscrever-se"
                    icon="o-paper-airplane"
                    class="btn-primary"
                    type="submit"
                    spinner="save"
                />
            </x-slot>
        </x-form>
    </x-modal>
</div>
