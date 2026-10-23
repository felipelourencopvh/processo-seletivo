{{-- Padrão do ecossistema STX Gov: quem está logado e o botão Sair no canto direito da barra superior. --}}
@php($user = filament()->auth()->user())
@if ($user)
    <div class="flex items-center gap-3">
        @foreach (\Illuminate\Support\Arr::except(filament()->getUserMenuItems(), ['account', 'logout', 'profile']) as $item)
            @if ($item->isVisible())
                <a href="{{ $item->getUrl() }}" class="hidden items-center gap-1.5 whitespace-nowrap rounded-lg px-3 py-2 text-sm font-semibold text-gray-600 hover:bg-gray-100 hover:text-primary-600 lg:inline-flex">
                    @if ($item->getIcon())
                        <x-filament::icon :icon="$item->getIcon()" class="h-4 w-4" />
                    @endif
                    {{ $item->getLabel() }}
                </a>
            @endif
        @endforeach
        <a href="{{ filament()->getProfileUrl() }}" class="flex items-center gap-2.5 rounded-xl px-2 py-1.5 hover:bg-gray-100" title="Meu perfil">
            <x-filament-panels::avatar.user :user="$user" class="h-8 w-8" />
            <span class="hidden text-start leading-tight md:block">
                <span class="block text-sm font-semibold text-gray-900">{{ $user->name }}</span>
                <span class="block text-xs text-gray-500">{{ $user->email }}</span>
            </span>
        </a>
        <form method="post" action="{{ filament()->getLogoutUrl() }}">
            @csrf
            <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg border border-gray-200 px-3 py-2 text-sm font-semibold text-gray-700 hover:border-primary-300 hover:text-primary-600">
                <x-filament::icon icon="heroicon-m-arrow-right-on-rectangle" class="h-4 w-4" />
                Sair
            </button>
        </form>
    </div>
@endif
