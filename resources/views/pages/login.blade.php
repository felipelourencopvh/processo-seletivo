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

<div class="mx-auto mt-20 md:w-96">
    <x-card title="Acessar Portal">
        <x-form wire:submit.prevent="login">
            <x-input label="E-Mail" icon="o-envelope" inline wire:model="email" />
            <x-input label="Senha" type="password" icon="o-key" inline wire:model="password" />
            <x-checkbox wire:model="remember" class="self-start">
                <x-slot:label>
                    {{ __('Remember Me') }}
                </x-slot>
            </x-checkbox>

            <x-slot:actions>
                <x-button label="Registre aqui" link="/registrar" />
                <x-button
                    label="{{ __('Login') }}"
                    type="submit"
                    icon="o-paper-airplane"
                    class="btn-primary"
                    spinner="login"
                />
            </x-slot>
        </x-form>
    </x-card>
</div>
