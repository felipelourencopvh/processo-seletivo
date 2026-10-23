<header class="relative z-20 bg-[#071c2c] text-white" id="header">
    <div class="mx-auto flex h-[76px] w-full max-w-[1240px] items-center justify-between gap-4 px-4 lg:px-8">
        <div class="flex items-center gap-3">
            <label for="main-drawer" class="cursor-pointer rounded-lg p-1.5 text-white/80 hover:bg-white/10 lg:hidden" aria-label="Abrir menu">
                <x-mary-icon name="o-bars-3" class="h-6 w-6" />
            </label>
            <a wire:navigate href="{{ route('home') }}" aria-label="STX Gov · Recrutamento — página inicial">
                <x-marca claro />
            </a>
        </div>

        <nav class="flex items-center gap-7 text-[14.5px] font-medium text-[#c9d6e2]">
            <a wire:navigate href="{{ route('home') }}" class="hidden hover:text-white lg:inline">Processos</a>
            <a href="{{ route('home') }}#como-funciona" class="hidden hover:text-white lg:inline">Como funciona</a>
            @auth
                <a wire:navigate href="{{ route('candidato.index') }}" class="hidden hover:text-white lg:inline">Minha área</a>
            @endauth
            <x-user-menu />
        </nav>
    </div>
</header>
