<header class="relative z-50 bg-white font-sans tracking-wide shadow-md">
    <div class="navbar mx-auto max-w-screen-2xl bg-base-100">
        <div class="navbar-start">
            {{-- <div class="dropdown"> --}}
            {{-- <div tabindex="0" role="button" class="btn btn-ghost lg:hidden"> --}}
            {{-- <svg --}}
            {{-- xmlns="http://www.w3.org/2000/svg" --}}
            {{-- class="h-5 w-5" --}}
            {{-- fill="none" --}}
            {{-- viewBox="0 0 24 24" --}}
            {{-- stroke="currentColor" --}}
            {{-- > --}}
            {{-- <path --}}
            {{-- stroke-linecap="round" --}}
            {{-- stroke-linejoin="round" --}}
            {{-- stroke-width="2" --}}
            {{-- d="M4 6h16M4 12h8m-8 6h16" --}}
            {{-- /> --}}
            {{-- </svg> --}}
            {{-- </div> --}}
            {{-- <ul --}}
            {{-- tabindex="0" --}}
            {{-- class="menu dropdown-content menu-sm z-[1] mt-3 w-52 rounded-box bg-base-100 p-2 shadow" --}}
            {{-- > --}}
            {{-- <li><a>Item 1</a></li> --}}

            {{-- <li><a>Item 3</a></li> --}}
            {{-- </ul> --}}
            {{-- </div> --}}
            <div>
                <a wire:navigate href="/"><img src="{{ asset('media/brasao.svg') }}" alt="logo" class="h-[40px]" /></a>
            </div>

            <div class="ml-5 flex flex-col items-center gap-0.5">
                <span class="me-3 text-sm font-bold text-primary">Processos Seletivos</span>
                <span class="me-3 text-sm font-bold text-primary">Prefeitura Porto Velho</span>
            </div>
        </div>
        <div class="navbar-center hidden lg:flex">
            @props([
                'external' => [
                    'Portal da Prefeitura' => 'https://www.portovelho.ro.gov.br',
                    'Transparência' => 'https://transparencia.portovelho.ro.gov.br',
                    'Ouvidoria' => 'https://ouvidoria.portovelho.ro.gov.br/',
                    'Acesso à informação' => 'https://falabr.cgu.gov.br/web/login?tipo=8&redirect=/manifestacao/criar?tipo=8',
                    'Denúncia Corrupção' => 'https://falabr.cgu.gov.br/login/RO/PortoVelho/Identificacao?idFormulario=4&tipo=1&ReturnUrl=%2fpublico%2fRO%2fPortoVelho%2fmanifestacao%2fRegistrarDadosManifestacao%3fidFormulario%3d4%26tipo%3d1%26origem%3didp%26modo%3d',
                ],
                'selected' => '',
            ])
            <ul class="menu menu-horizontal px-1">
                @foreach ($external as $label => $url)
                    <li>
                        <a
                            class="{{ Request::is(trim($url, '/')) ? 'font-bold text-blue-800' : '' }} text-sm font-medium text-primary hover:bg-transparent hover:text-blue-800"
                            href="{{ $url }}"
                            target="_blank"
                        >
                            {{ $label }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="navbar-end">
            @auth
                <x-dropdown>
                    <x-slot:trigger>
                        <x-avatar :image="auth()->user()->avatar_url" class="!w-8 !rounded-lg" />
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
            @else
                <x-button class="btn-primary btn-sm" label="Entrar" icon="o-user" link="/login" />
            @endauth
        </div>
    </div>
</header>
