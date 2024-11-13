<?php

use Livewire\Attributes\Validate;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\Volt\Component;

new class () extends Component {
    use Livewire\WithFileUploads;
    use Mary\Traits\Toast;

    public string                $uuid;
    public ?App\Models\Inscricao $inscricao;

    #[Validate('required|min:3')]
    public ?string $recurso;
    public bool    $habilitado = false;

    public function mount()
    {
        $this->inscricao = App\Models\Inscricao::query()
            ->with(['documentos.cargoDocumento', 'cargo', 'processo'])
            ->where('id', $this->uuid)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $this->recurso = $this->inscricao->recurso;

        $this->habilitado = $this->inscricao->processo->isDentroPeriodoRecurso();
    }

    public function save()
    {
        $this->validate();
        $this->inscricao->update(['recurso' => $this->recurso]);
        $this->success('Recurso enviado com sucesso', redirectTo: '/candidato');
    }
} ?>

<div>
    <div class="grid gap-8 lg:grid-cols-1">
        {{-- INFO --}}
        <x-card title="Dados da Inscrição" separator shadow>
            <x-input label="Processo Seletivo" value="{{ $inscricao->processo->titulo }}" readonly />
            <x-input label="Número da Inscrição" value="{{ $inscricao->id }}" readonly />
            <x-form wire:submit="save">
                @php
                    $config = [
                        'plugins' => 'autoresize',
                        'min_height' => 250,
                        'max_height' => 500,
                        'statusbar' => false,
                        'toolbar' => 'undo redo | bold | alignleft aligncenter alignright alignjustify | outdent indent italic',
                        'quickbars_selection_toolbar' => 'bold link italic',
                    ];
                @endphp

                <x-editor
                    wire:model="recurso"
                    label="Recurso"
                    hint="Recurso disponível para alteração até {{ $inscricao->processo->data_recurso_fim->format('d/m/Y h:i') }}"
                    :config="$config"
                    :readonly="! $this->habilitado"
                />

                @if ($this->habilitado)
                    <x-slot:actions>
                        <x-button.back />
                        <x-button
                            label="Enviar Recurso"
                            icon="o-paper-airplane"
                            class="btn-primary"
                            type="submit"
                            spinner="save"
                        />
                    </x-slot>
                @endif
            </x-form>
        </x-card>
    </div>
</div>
