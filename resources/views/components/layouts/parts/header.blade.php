<header class="br-header mb-4" id="header" data-sticky="data-sticky">
    <div class="container-lg">
        <div class="header-top">
            <div class="header-logo">
                <x-marca sub="false" />
                <span class="br-divider vertical"></span>
                <div class="header-sign">Recrutamento</div>
            </div>
            <div class="header-actions">
                <div class="header-links dropdown">
                    <button
                        class="br-button circle small"
                        type="button"
                        data-toggle="dropdown"
                        aria-label="Abrir Acesso Rápido"
                    >
                        <i class="fas fa-ellipsis-v" aria-hidden="true"></i>
                    </button>
                    <div class="br-list">
                        <div class="header">
                            <div class="title">Acesso Rápido</div>
                        </div>

                        @props([
                            'external' => [
                                'Transparência' => '#',
                                'Ouvidoria' => '#',
                                'Acesso à informação' => 'https://falabr.cgu.gov.br/web/login?tipo=8&redirect=/manifestacao/criar?tipo=8',
                            ],
                            'selected' => '',
                        ])
                        @foreach ($external as $label => $url)
                            <a class="br-item" href="{{ $url }}">{{ $label }}</a>
                        @endforeach
                    </div>
                </div>
                <span class="br-divider vertical mx-half mx-sm-1"></span>
                <div class="header-functions dropdown">
                    <button
                        class="br-button circle small"
                        type="button"
                        data-toggle="dropdown"
                        aria-label="Abrir Funcionalidades do Sistema"
                    >
                        <i class="fas fa-th" aria-hidden="true"></i>
                    </button>
                    {{-- <div class="br-list"> --}}
                    {{-- <div class="header"> --}}
                    {{-- <div class="title">Funcionalidades do Sistema</div> --}}
                    {{-- </div> --}}
                    {{-- <div class="br-item"> --}}
                    {{-- <button --}}
                    {{-- class="br-button circle small" --}}
                    {{-- type="button" --}}
                    {{-- aria-label="Funcionalidade 1" --}}
                    {{-- control-id="ControlID-1" --}}
                    {{-- > --}}
                    {{-- <i class="fas fa-chart-bar" aria-hidden="true"></i> --}}
                    {{-- <span class="text">Funcionalidade 1</span> --}}
                    {{-- </button> --}}
                    {{-- </div> --}}
                    {{-- <div class="br-item"> --}}
                    {{-- <button --}}
                    {{-- class="br-button circle small" --}}
                    {{-- type="button" --}}
                    {{-- aria-label="Funcionalidade 2" --}}
                    {{-- control-id="ControlID-2" --}}
                    {{-- > --}}
                    {{-- <i class="fas fa-headset" aria-hidden="true"></i> --}}
                    {{-- <span class="text">Funcionalidade 2</span> --}}
                    {{-- </button> --}}
                    {{-- </div> --}}
                    {{-- <div class="br-item"> --}}
                    {{-- <button --}}
                    {{-- class="br-button circle small" --}}
                    {{-- type="button" --}}
                    {{-- aria-label="Funcionalidade 3" --}}
                    {{-- control-id="ControlID-3" --}}
                    {{-- > --}}
                    {{-- <i class="fas fa-comment" aria-hidden="true"></i> --}}
                    {{-- <span class="text">Funcionalidade 3</span> --}}
                    {{-- </button> --}}
                    {{-- </div> --}}
                    {{-- <div class="br-item"> --}}
                    {{-- <button --}}
                    {{-- class="br-button circle small" --}}
                    {{-- type="button" --}}
                    {{-- aria-label="Funcionalidade 4" --}}
                    {{-- control-id="ControlID-4" --}}
                    {{-- > --}}
                    {{-- <i class="fas fa-adjust" aria-hidden="true"></i> --}}
                    {{-- <span class="text">Funcionalidade 4</span> --}}
                    {{-- </button> --}}
                    {{-- </div> --}}
                    {{-- </div> --}}
                </div>
                <div class="header-search-trigger">
                    <button
                        class="br-button circle"
                        type="button"
                        aria-label="Abrir Busca"
                        data-toggle="search"
                        data-target=".header-search"
                        control-id="ControlID-5"
                    >
                        <i class="fas fa-search" aria-hidden="true"></i>
                    </button>
                </div>
                <div class="header-login">
                    <div class="header-sign-in">
                        <x-user-menu />
                    </div>
                    <div class="header-avatar"></div>
                </div>
            </div>
        </div>
        <div class="header-bottom">
            <div class="header-menu">
                <div class="header-menu-trigger" id="header-navigation">
                    <label for="main-drawer" class="cursor-pointer">
                        <x-svg
                            for="main-drawer"
                            class="br-button small circle inline h-9 w-9 text-primary hover:bg-base-200"
                            name="heroicon-o-bars-3"
                        />
                    </label>
                </div>
                <div class="header-info">
                    <div class="header-title">
                        <a wire:navigate href="{{ route('home') }}">{{ config('app.name') }}</a>
                    </div>
                    {{-- <div class="header-subtitle">Subtítulo do Header</div> --}}
                </div>
            </div>
            {{-- <div class="header-search" id="main-searchbox"> --}}
            {{-- <div class="br-input has-icon"> --}}
            {{-- <label for="searchbox">Texto da pesquisa</label> --}}
            {{-- <input --}}
            {{-- id="searchbox" --}}
            {{-- type="text" --}}
            {{-- placeholder="O que você procura?" --}}
            {{-- control-id="ControlID-8" --}}
            {{-- class="" --}}
            {{-- /> --}}
            {{-- <button --}}
            {{-- class="br-button circle small" --}}
            {{-- type="button" --}}
            {{-- aria-label="Pesquisar" --}}
            {{-- control-id="ControlID-9" --}}
            {{-- > --}}
            {{-- <i class="fas fa-search" aria-hidden="true"></i> --}}
            {{-- </button> --}}
            {{-- </div> --}}
            {{-- <button --}}
            {{-- class="br-button circle search-close ml-1" --}}
            {{-- type="button" --}}
            {{-- aria-label="Fechar Busca" --}}
            {{-- data-dismiss="search" --}}
            {{-- control-id="ControlID-10" --}}
            {{-- > --}}
            {{-- <icon></icon> --}}
            {{-- <i class="fas fa-times" aria-hidden="true"></i> --}}
            {{-- </button> --}}
            {{-- </div> --}}
        </div>
    </div>
</header>
