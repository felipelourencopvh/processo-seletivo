<?php

use App\Models\Inscricao;
use App\Models\InscricaoDocumento;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;

new class () extends Component {
    use WithFileUploads;
    use Toast;

    public string     $uuid;
    public ?Inscricao $inscricao;
    public array      $file              = [];
    public ?string    $recurso           = null;
    public ?string    $analiseRecurso    = null;
    public ?string    $criterioInscricao = null;
    public bool       $habilitado        = false;

    public function mount()
    {
        $this->inscricao = Inscricao::query()
            ->with(['documentos.cargoDocumento', 'cargo', 'processo'])
            ->where('id', $this->uuid)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $this->file              = $this->inscricao->documentos->pluck('documento', 'id')->toArray();
        $this->recurso           = $this->inscricao->recurso;
        $this->analiseRecurso    = $this->inscricao->analise_recurso;
        $this->criterioInscricao = $this->inscricao->criterio_inscricao;
        $this->habilitado        = $this->inscricao->processo->status->isAberto();
    }

    public function save()
    {
        foreach ($this->file as $key => $file) {
            if ($file instanceof TemporaryUploadedFile) {
                $documento          = InscricaoDocumento::query()->where('id', $key)->where('inscricao_id', $this->uuid)->first();
                $documento->enviado = true;
                $documento->save();
                $documento->addMedia($file->getRealPath())->usingName($file->getClientOriginalName())->toMediaCollection('default');
            }
        }

        $this->success('Documento enviado com sucesso');
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
            <x-input label="Situação" value="{{ $inscricao->situacao }}" readonly />
        </x-card>

        @if ( ! empty($criterioInscricao) && $inscricao->processo->data_homologacao <= now())
            <x-card title="Critério de Inscrição" separator shadow>
                {!! $this->criterioInscricao !!}
            </x-card>
        @endif

        @if ($recurso)
            <x-card title="Recurso" separator shadow>
                <x-editor wire:model="recurso" label="Recurso" disabled />
                @if ($analiseRecurso && $inscricao->processo->isRecursoLiberado())
                    <x-mary-menu-separator />
                    <x-editor wire:model="analiseRecurso" label="Resultado da Análise" disabled />
                @endif
            </x-card>
        @endif

        @if ($habilitado)
            <x-card title="Envio de Documentos" subtitle="clique em Salvar para confirmar o envio" separator shadow>
                <x-form wire:submit="save">
                    @foreach ($inscricao->documentos->groupBy('processo_cargo_documento_id') as $items)
                        @php($pontuacaoMaxima = $items->sum('pontuacao_previa'))
                        <x-card
                            class="bg-blue-5 mb-10"
                            title="{{$items->first()->cargoDocumento->documento}}"
                            subtitle="{{ $pontuacaoMaxima > 0 ? 'Pontuação Máxima: ' .  $items->sum('pontuacao_previa') : null }}"
                        >
                            @foreach ($items as $documento)
                                @if ($documento->enviado)
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
                        <x-button
                            label="Salvar"
                            icon="o-paper-airplane"
                            class="btn-primary"
                            type="submit"
                            spinner="save"
                        />
                    </x-slot>
                </x-form>
            </x-card>
        @endif

        <x-card title="Documentos Enviados" separator shadow>
            <x-form wire:submit="save">
                @foreach ($inscricao->documentos->where('enviado', true)->groupBy('processo_cargo_documento_id') as $items)
                    @php($pontuacaoMaxima = $items->sum('pontuacao_previa'))
                    <x-card
                        class="bg-blue-5 mb-10"
                        title="{{$items->first()->cargoDocumento->documento}}"
                        subtitle="{{ $pontuacaoMaxima > 0 ? 'Pontuação Máxima: ' .  $items->sum('pontuacao_previa') : null }}"
                    >
                        @foreach ($items as $documento)
                            @if ($documento->enviado)
                                <x-button
                                    label="Clique aqui para baixar"
                                    class="btn-success btn-sm"
                                    :link="$documento->getFirstMediaUrl()"
                                    external
                                />
                            @endif
                        @endforeach
                    </x-card>
                @endforeach
            </x-form>
        </x-card>
    </div>
</div>
