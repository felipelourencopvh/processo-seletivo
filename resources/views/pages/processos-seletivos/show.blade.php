<?php

use Livewire\Volt\Component;
use Mary\Traits\Toast;

new class () extends Component {
    use Toast;

    public App\Models\Processo $processo;

    public function mount()
    {
        $this->processo->load('anexos');
    }
} ?>

<div>
    <x-header title="{{ $processo->titulo }}" progress-indicator separator />

    <x-card title="Informações" separator>
        <div class="card-body">
            {!! $processo->descricao !!}
        </div>
    </x-card>

    @if ($processo->status->isAberto())
        <x-card title="Inscreva-se agora" separator class="mt-10">
            <div class="card-body">
                <x-button
                    label="Clique aqui para se inscrever"
                    class="btn-primary"
                    :link="route('processos-seletivos.inscrever', $processo)"
                />
            </div>
        </x-card>
    @endif

    @foreach ($processo->anexos->groupBy('tipo') as $tipo => $anexos)
        @php
            $tipo = match ($tipo) {
                'editais' => 'Editais e Erratas',
                'resultados' => 'Resultados, Resoluções e Editais de Convocação',
                'anexos' => 'Anexos'
            };
        @endphp

        <x-card title="{{ $tipo }}" separator class="mt-10">
            @foreach ($anexos as $anexo)
                <x-list-item :item="$anexo" no-separator no-hover>
                    <x-slot:avatar>
                        <x-mary-icon name="feathericon.file-text" class="h-12 w-12" />
                    </x-slot>
                    <x-slot:value>{{ $anexo->nome }}</x-slot>
                    <x-slot:sub-value>
                        Publicado em {{ $anexo->data_publicacao->format('d/m/Y') }}
                    </x-slot>
                    <x-slot:actions>
                        <x-button
                            label="Clique aqui para baixar"
                            icon="s-cloud-arrow-down"
                            class="btn-primary btn-sm"
                            :link="$anexo->getFirstMediaUrl()"
                            responsive
                            external
                        />
                    </x-slot>
                </x-list-item>
            @endforeach
        </x-card>
    @endforeach
</div>
