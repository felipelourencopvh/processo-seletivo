@auth
    <div class="flex items-center gap-1 px-3 py-2 lg:bg-base-200">
        <x-mary-icon name="s-user" class="hidden text-green-600 lg:flex" label="Acesso Público" />
        <div class="divider divider-horizontal hidden lg:flex"></div>
        <div class="hidden lg:flex">
            {{ $user->name }}
        </div>

        <x-dropdown right>
            <x-slot:trigger>
                <div class="bg-primary-500 flex items-center gap-2">
                    <div class="avatar">
                        <div @class(['w-7 rounded-full'])>
                            <img src="{{ $user->avatar_url }}" />
                        </div>
                    </div>
                    <x-mary-icon class="text-primary" name="fas.caret-down" />
                </div>
            </x-slot>
            <div class="w-64">
                <x-avatar
                    :image="auth()->user()->avatar_url"
                    :title="auth()->user()->name"
                    :subtitle="auth()->user()->email"
                    class="!w-10"
                />
                <x-menu-separator />
                <x-menu-item icon="o-user" :title="__('Meu Perfil')" link="{{ route('candidato.index') }}" />
                <x-menu-separator />
                <x-menu-item icon="o-power" :label="'Sair'" link="/logout" no-wire-navigate />
            </div>
        </x-dropdown>
    </div>
@else
    <x-button class="btn-primary btn-sm" label="Entrar" icon="o-user" link="/login" />
@endauth
