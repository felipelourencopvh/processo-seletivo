<?php

use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\Volt\Component;

new class () extends Component {
    use Livewire\WithFileUploads;

    public string                $uuid;
    public ?App\Models\Inscricao $inscricao;
    public array                 $file = [];

    public function mount()
    {
        $this->inscricao = App\Models\Inscricao::query()
            ->with(['documentos.cargoDocumento', 'cargo', 'processo'])
            ->where('id', $this->uuid)
            ->where('user_id', auth()->id())
            ->firstOrFail();
    }

    public function save()
    {
        foreach ($this->file as $key => $file) {
            if ($file instanceof TemporaryUploadedFile) {
                $documento = App\Models\InscricaoDocumento::query()->where('id', $key)->where('inscricao_id', $this->uuid)->first();
                $documento->enviado = true;
                $documento->save();
                $documento->addMedia($file->getRealPath())->usingName($file->getClientOriginalName())->toMediaCollection('default');
            }
        }
    }
} ?>

<div>
    <div class="grid gap-8 lg:grid-cols-1">
        {{-- INFO --}}
        <x-card title="Dados da Inscrição" separator shadow>
            <x-input label="Processo Seletivo" value="{{ $inscricao->processo->titulo }}" readonly />
            <x-input label="Número da Inscrição" value="{{ $inscricao->id }}" readonly />
            <x-input label="Cota" value="{{ $inscricao->cota }}" readonly />
            <x-input label="Cargo" value="{{ $inscricao->cargo->nome }}" readonly />
            <x-input label="Localidade" value="{{ $inscricao->cargo->localidade }}" readonly />
            <x-input label="Nível de Ensino" value="{{ $inscricao->cargo->nivel->getLabel() }}" readonly />
            <x-input label="Data da Inscrição" value="{{ $inscricao->created_at->format('d/m/Y H:i') }}" readonly />
        </x-card>

        <x-card title="Envio de Documentos" subtitle="clique em Salvar para confirmar o envio" separator shadow>
            <x-form wire:submit="save">
                @foreach ($inscricao->documentos->groupBy('processo_cargo_documento_id') as $items)
                    <x-card
                        class="bg-blue-5 mb-10"
                        title="{{$items->first()->cargoDocumento->documento}}"
                        subtitle="Pontuação por Documento: {{  $items->first()->pontuacao_previa }}"
                    >
                        @foreach ($items as $documento)
                            @if ($documento->hasMedia())
                                <x-button
                                    label="Clique aqui para baixar"
                                    class="btn-success btn-sm"
                                    :link="$documento->getFirstMediaUrl()"
                                    external
                                />
                            @else
                                <x-file
                                    wire:model="file.{{$documento->id}}"
                                    hint="Somente arquivo PDF"
                                    accept="application/pdf"
                                />
                            @endif
                        @endforeach
                    </x-card>
                @endforeach

                <x-slot:actions>
                    <x-button label="Salvar" icon="o-paper-airplane" class="btn-primary" type="submit" spinner="save" />
                </x-slot>
            </x-form>
        </x-card>
    </div>
</div>
