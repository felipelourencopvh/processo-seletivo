@auth
    <x-dropdown right>
        <x-slot:trigger>
            <button type="button" class="flex items-center gap-2.5 rounded-[10px] border border-white/20 py-1.5 pl-1.5 pr-3 text-white hover:bg-white/10">
                <span class="avatar">
                    <span class="w-7 rounded-full">
                        <img src="{{ $user->avatar_url }}" alt="" />
                    </span>
                </span>
                <span class="hidden max-w-[12rem] truncate text-sm font-semibold sm:inline">{{ $user->name }}</span>
                <x-mary-icon name="o-chevron-down" class="h-4 w-4 text-white/60" />
            </button>
        </x-slot>
        <div class="w-64 text-[#0b1f2e]">
            <x-avatar
                :image="auth()->user()->avatar_url"
                :title="auth()->user()->name"
                :subtitle="auth()->user()->email"
                class="!w-10"
            />
            <x-menu-separator />
            <x-menu-item icon="o-user" :title="__('Minha área')" link="{{ route('candidato.index') }}" />
            <x-menu-separator />
            <x-menu-item icon="o-power" :label="'Sair'" link="/logout" no-wire-navigate />
        </div>
    </x-dropdown>
@else
    <a
        wire:navigate
        href="/login"
        class="inline-flex items-center gap-2 rounded-[10px] border border-white/20 px-4 py-2.5 text-[14.5px] font-semibold text-white hover:bg-white/10"
    >
        <x-mary-icon name="o-user" class="h-4 w-4" />
        Entrar
    </a>
@endauth
