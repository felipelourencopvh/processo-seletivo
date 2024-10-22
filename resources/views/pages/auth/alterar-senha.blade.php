<?php

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
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
    public string $email;
    public string $token;

    #[Validate('required|min:8|confirmed')]
    public $password;
    public $password_confirmation;

    public function mount(Request $request)
    {
        $this->email = $request->input('email');
    }

    public function alterar(Request $request)
    {
        $this->validate();
        $status = Password::reset(
            ['email' => $this->email, 'password' => $this->password, 'password_confirmation' => $this->password_confirmation, 'token' => $this->token],
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            },
        );

        if ($status === Password::PASSWORD_RESET) {
            $this->success(__($status), redirectTo: '/login');
        } else {
            $this->error(__($status));
        }
    }
}; ?>

<div class="mx-auto mt-20 md:w-[32rem]">
    <div class="card rounded-lg bg-base-100 p-5">
        <div class="mb-5 w-full text-center text-5xl">Acesso ao sistema</div>
        <x-form wire:submit.prevent="alterar">
            <x-input label="E-Mail" icon="o-envelope" hint="Digite seu email" wire:model="email" />
            <x-password
                label="Senha"
                type="password"
                hint="Digite sua senha de segurança"
                password-icon="o-lock-closed"
                password-visible-icon="o-lock-open"
                wire:model="password"
            />
            <x-password
                label="Senha"
                type="password"
                hint="Confirme sua senha"
                password-icon="o-lock-closed"
                password-visible-icon="o-lock-open"
                wire:model="password_confirmation"
            />

            <x-button
                label="Alterar senha"
                type="submit"
                icon="o-paper-airplane"
                class="btn-primary btn-block mt-5"
                spinner="login"
            />

            <hr class="my-3" />

            <x-button class="text-primary" label="Voltar" link="/login" />
        </x-form>
    </div>
</div>
