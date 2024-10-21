<?php

use Livewire\Volt\Component;

new class () extends Component {
    public ?App\Models\User $user;

    public function mount()
    {
        $this->user = auth()->user();
    }

    public function headers(): array
    {
        return [
            ['key' => 'uuid', 'label' => '#'],
            ['key' => 'processo.titulo', 'label' => 'Processo'],
            ['key' => 'cargo.nome', 'label' => 'Cargo'],
            ['key' => 'cargo.localidade', 'label' => 'Localidade'],
            ['key' => 'cota', 'label' => 'Cota'],
            ['key' => 'situacao', 'label' => 'Situação'],
        ];
    }

    public function with(): array
    {
        return [
            'inscricoes' => App\Models\Inscricao::query()->with(['cargo', 'processo'])->whereUserId($this->user->id)->get(),
            'headers' => $this->headers(),
        ];
    }
} ?>

<div>
    <x-header title="Painel do Candidato" separator />

    <div class="grid gap-8 lg:grid-cols-2">
        {{-- INFO --}}
        <x-card title="Dados Pessoais" separator shadow>
            <x-slot:menu>
                <x-button
                    label="Editar Dados Pessoais"
                    link="{{ route('candidato.edit')  }}"
                    icon="o-pencil"
                    class="btn-primary"
                    responsive
                />
            </x-slot>
            <x-avatar :image="$user->avatar_url" class="!w-20">
                <x-slot:title class="pl-2">
                    {{ $user->name }}
                </x-slot>
                <x-slot:subtitle class="flex flex-col gap-2 p-2 pl-2">
                    <x-mary-icon name="o-envelope" :label="auth()->user()->email" />
                </x-slot>
            </x-avatar>
        </x-card>
    </div>

    <x-card title="Inscrições" separator shadow class="mt-8">
        <x-table :rows="$inscricoes" :headers="$headers" link="/candidato/inscricoes/{id}">
            @scope('actions', $cargo)
                <div class="flex items-center space-x-2">
                    <x-button
                        label="Selecionar"
                        class="btn-primary btn-xs"
                        link="/candidato/inscricoes/{{ $cargo->id }}"
                    />
                </div>
            @endscope
        </x-table>

        @if (! $inscricoes->count())
            <x-mary-icon name="o-list-bullet" label="Nenhuma inscrição." class="mt-5 text-gray-400" />
        @endif
    </x-card>
</div>
