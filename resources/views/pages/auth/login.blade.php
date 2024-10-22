<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;
use Illuminate\Http\Request;
use Mary\Traits\Toast;

new
#[Title('Login')]
#[Layout('components.layouts.empty')]
class extends Component {
    use Toast;

    #[Validate('required|email')]
    public string $email = '';

    #[Validate('required|string')]
    public string $password = '';

    public bool $remember = false;

    public function login(Request $request)
    {
        $this->validate();

        if (auth()->user()) {
            return redirect('/');
        }

        if ( ! Auth::attempt($this->validate(), $this->remember)) {
            $this->addError('email', __('auth.failed'));

            return null;
        }

        $request->session()->regenerate();

        return redirect('/');
    }
}; ?>

<div class="mx-auto mt-20 md:w-[32rem]">
    <div class="card rounded-lg bg-base-100 p-5">
        <div class="mb-5 w-full text-center text-5xl">Acesso ao sistema</div>
        <x-form wire:submit.prevent="login">
            <x-input label="E-Mail" icon="o-envelope" hint="Digite seu email" wire:model="email" />
            <x-password
                label="Senha"
                type="password"
                hint="Digite sua senha de segurança"
                password-icon="o-lock-closed"
                password-visible-icon="o-lock-open"
                wire:model="password"
            />

            <a class="mb-3 font-bold underline" href="/recuperar-senha" wire:navigate>Esqueceu a senha?</a>

            <x-checkbox wire:model="remember" class="self-start">
                <x-slot:label>Manter-me conectado</x-slot>
            </x-checkbox>

            <x-button
                label="{{ __('Login') }}"
                type="submit"
                icon="o-paper-airplane"
                class="btn-primary btn-block mt-5"
                spinner="login"
            />

            <hr class="my-3" />

            <x-button class="text-primary" label="Primeiro acesso?" link="/registrar" />
        </x-form>
    </div>
</div>
