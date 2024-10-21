<?php

use Livewire\Volt\Component;
use Mary\Traits\Toast;
use App\Models\User;

new class () extends Component {
    use Toast;

    public App\Livewire\Forms\EditUserForm $form;
    public ?User                           $user;
    public array                           $listaEstados = [
        ['sigla' => 'AC', 'nome' => 'Acre'],
        ['sigla' => 'AL', 'nome' => 'Alagoas'],
        ['sigla' => 'AP', 'nome' => 'Amapá'],
        ['sigla' => 'AM', 'nome' => 'Amazonas'],
        ['sigla' => 'BA', 'nome' => 'Bahia'],
        ['sigla' => 'CE', 'nome' => 'Ceará'],
        ['sigla' => 'DF', 'nome' => 'Distrito Federal'],
        ['sigla' => 'ES', 'nome' => 'Espirito Santo'],
        ['sigla' => 'GO', 'nome' => 'Goiás'],
        ['sigla' => 'MA', 'nome' => 'Maranhão'],
        ['sigla' => 'MS', 'nome' => 'Mato Grosso do Sul'],
        ['sigla' => 'MT', 'nome' => 'Mato Grosso'],
        ['sigla' => 'MG', 'nome' => 'Minas Gerais'],
        ['sigla' => 'PA', 'nome' => 'Pará'],
        ['sigla' => 'PB', 'nome' => 'Paraíba'],
        ['sigla' => 'PR', 'nome' => 'Paraná'],
        ['sigla' => 'PE', 'nome' => 'Pernambuco'],
        ['sigla' => 'PI', 'nome' => 'Piauí'],
        ['sigla' => 'RJ', 'nome' => 'Rio de Janeiro'],
        ['sigla' => 'RN', 'nome' => 'Rio Grande do Norte'],
        ['sigla' => 'RS', 'nome' => 'Rio Grande do Sul'],
        ['sigla' => 'RO', 'nome' => 'Rondônia'],
        ['sigla' => 'RR', 'nome' => 'Roraima'],
        ['sigla' => 'SC', 'nome' => 'Santa Catarina'],
        ['sigla' => 'SP', 'nome' => 'São Paulo'],
        ['sigla' => 'SE', 'nome' => 'Sergipe'],
        ['sigla' => 'TO', 'nome' => 'Tocantins'],
    ];

    public function mount(): void
    {
        $this->user = auth()->user();
        $this->user->load('info');
        $this->form->fill($this->user);
        $this->form->fill($this->user->info);
    }

    public function buscaCep(): void
    {
        if (strlen($this->form->endereco_cep) === 9) {
            $cep = (new App\Actions\GetAddressFromCEPAction($this->form->endereco_cep))->execute();
            $this->form->endereco_logradouro = $cep['logradouro'] ?? null;
            $this->form->endereco_complemento = $cep['complemento'] ?? null;
            $this->form->endereco_bairro = $cep['bairro'] ?? null;
            $this->form->endereco_cidade = $cep['localidade'] ?? null;
            $this->form->endereco_uf = $cep['uf'] ?? null;
        }
    }

    public function save()
    {
        $data = $this->form->validate();
        if (User::query()->where('email', $this->form->email)->where('id', '!=', $this->user->id)->exists()) {
            $this->error('E-mail já em uso', redirectTo: '/candidato/editar');
        }

        $this->user->update($data);
        $this->user->info->update($data);

        $this->success('Informações alteradas com sucesso!', redirectTo: '/candidato');
    }

    public function updatePassword()
    {
        $data = $this->form->validateChangePassword();
        $this->user->password = bcrypt($data->password);
        $this->user->save();
    }
}
?>

<div>
    <x-card title="Editar Cadastro">
        <x-form wire:submit="save">
            <x-input label="Nome Completo" wire:model="form.name" required />
            <x-input label="E-Mail" wire:model="form.email" required />
            <x-input
                label="CPF"
                wire:model="form.documento"
                x-mask="999.999.999-99"
                required
                placeholder="___.___.___-__"
            />
            <x-mary-datetime label="Data de Nascimento" wire:model="form.data_nascimento" icon="o-calendar" required />

            <x-input label="RG" wire:model="form.rg" required />
            <x-mary-select
                label="Sexo"
                :options="[['id' => 'Masculino'], ['id' => 'Feminino']]"
                option-value="id"
                option-label="id"
                wire:model="form.sexo"
                required
            />

            <x-input label="Filiação, Nome da Mãe" wire:model="form.filiacao_mae" required />
            <x-input label="Filiação, Nome do Pai" wire:model="form.filiacao_pai" />

            <x-input
                label="Telefone Residencial"
                wire:model="form.telefone_residencial"
                x-mask="(99) 9999-9999"
                placeholder="(__) ____-____"
            />
            <x-input
                label="Telefone Celular"
                wire:model="form.telefone_celular"
                x-mask="(99) 99999-9999"
                required
                placeholder="(__) _____-____"
            />
            <div class="grid grid-cols-1 gap-x-6 gap-y-8 md:grid-cols-2">
                <x-input
                    label="CEP"
                    x-mask="99999-999"
                    placeholder="_____-___"
                    wire:model="form.endereco_cep"
                    wire:blur="buscaCep()"
                    required
                />
                <x-input label="Logradouro" wire:model="form.endereco_logradouro" required />
                <x-input label="Número" wire:model="form.endereco_numero" required />
                <x-input label="Bairro" wire:model="form.endereco_bairro" required />
                <div class="md:col-span-2">
                    <x-input label="Complemento" wire:model="form.endereco_complemento" required />
                </div>
                <x-input label="Cidade" wire:model="form.endereco_cidade" required />
                <x-mary-select
                    label="UF"
                    :options="$listaEstados"
                    option-value="sigla"
                    option-label="nome"
                    placeholder="Selecione um Estado"
                    placeholder-value="0"
                    wire:model="form.endereco_uf"
                    required
                />
            </div>
            <x-slot:actions>
                <x-button.back link="{{ route('candidato.index') }}" />
                <x-button.submit />
            </x-slot>
        </x-form>
    </x-card>

    <x-card title="Alterar Senha" class="mt-5">
        <x-form wire:submit="updatePassword">
            <x-input label="Senha" type="password" wire:model="form.password" />
            <x-input label="Confirme a Senha" type="password" wire:model="form.password_confirmation" />
            <x-slot:actions>
                <x-button.back link="{{ route('candidato.index') }}" />
                <x-button.submit />
            </x-slot>
        </x-form>
    </x-card>
</div>
