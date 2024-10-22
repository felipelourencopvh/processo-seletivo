<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Mary\Traits\Toast;
use App\Models\User;

new
#[Layout('components.layouts.empty')]
class () extends Component {
    use Toast;

    public App\Livewire\Forms\NewUserForm $form;
    public ?User                          $user;
    public array                          $listaEstados = [
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

    public function save(App\Actions\CreateNewUserAction $action)
    {
        $data = $this->form->validate();

        $action->handle($this->form);

        $this->success('Usuário registrado com sucesso!', redirectTo: '/login');
    }
}
?>

<div class="mx-auto mt-20 md:w-5/6">
    <x-card title="Acesso ao sistema" subtitle="Novo Cadastro">
        <x-form wire:submit="save">
            <x-input label="Nome Completo" wire:model.blur="form.name" required />
            <x-input label="E-Mail" wire:model.blur="form.email" required />
            <x-input label="Senha" type="password" wire:model="form.password" />
            <x-input label="Confirme a Senha" type="password" wire:model="form.password_confirmation" />
            <x-input
                label="CPF"
                wire:model.blur="form.documento"
                x-mask="999.999.999-99"
                required
                placeholder="___.___.___-__"
            />
            <x-mary-datetime
                label="Data de Nascimento"
                wire:model.blur="form.data_nascimento"
                icon="o-calendar"
                required
            />

            <x-input label="RG" wire:model.blur="form.rg" required />
            <x-mary-select
                label="Sexo"
                :options="[['id' => 'Masculino'], ['id' => 'Feminino']]"
                option-value="id"
                option-label="id"
                wire:model="form.sexo"
                required
            />

            <x-input label="Filiação, Nome da Mãe" wire:model.blur="form.filiacao_mae" required />
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

            <x-slot name="actions">
                <x-button.back link="/" />
                <x-button.submit label="Registrar" />
            </x-slot>
        </x-form>
    </x-card>
</div>
