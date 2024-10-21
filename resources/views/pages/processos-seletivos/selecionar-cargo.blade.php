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

    public function save(): void
    {
        $this->validate();
        $inscricao = new App\Models\Inscricao();
        $inscricao->situacao = 'Pendente';
        $inscricao->user_id = auth()->id();
        $inscricao->processo_id = $this->cargo->processo_id;
        $inscricao->cargo_id = $this->cargo->id;
        $inscricao->cota = $this->cota;
        $inscricao->save();

        foreach ($this->cargo->documentos()->get() as $documento) {
            for ($x = 1; $x <= $documento->quantidade_maxima; $x++) {
                $inscricaoDoc = new App\Models\InscricaoDocumento();
                $inscricaoDoc->documento = $documento->documento.' ['.$x.']';
                $inscricaoDoc->enviado = false;
                $inscricaoDoc->aprovado = false;
                $inscricaoDoc->pontuacao = 0;
                $inscricaoDoc->pontuacao_previa = $documento->pontuacao;
                $inscricaoDoc->inscricao_id = $inscricao->id;
                $inscricaoDoc->processo_cargo_documento_id = $documento->id;
                $inscricaoDoc->save();
            }
        }

        //        try {
        //            Mail::to(auth()->user()->email)->send(new App\Mail\InscricaoConfirmadaMail($inscricao));
        //        } catch (Exception $e) {
        //        }

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
