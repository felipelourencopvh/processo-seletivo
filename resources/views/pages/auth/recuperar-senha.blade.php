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

    public function recuperar(Request $request)
    {
        $this->validate();

        $status = Password::sendResetLink(
            ['email' => $this->email]
        );

        if ($status === Password::RESET_LINK_SENT) {
            $this->success(__($status), redirectTo: '/login');
        } else {
            $this->error(__($status));
        }
    }
}; ?>

<div class="mx-auto mt-8 md:w-[32rem]">
    <div class="card rounded-2xl bg-base-100 p-7 shadow-[0_30px_60px_-30px_#000]">
        <div class="mb-6 w-full text-center font-display text-3xl font-bold tracking-tight text-[#071c2c]">Acesso ao sistema</div>
        <x-form wire:submit.prevent="recuperar">
            <x-input label="E-Mail" icon="o-envelope" hint="Digite seu email" wire:model="email" />

            <x-button
                label="Recuperar senha"
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
