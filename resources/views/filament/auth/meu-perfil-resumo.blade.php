{{-- Cartão lateral do "Meu perfil": quem é, o papel no sistema e os processos em que está na comissão. --}}
@php
    $user = filament()->auth()->user();
    $papeis = $user->getRoleNames()->map(fn (string $p): string => match ($p) {
        'super_admin' => 'Administrador do sistema',
        'panel_user' => 'Comissão',
        default => str($p)->replace('_', ' ')->ucfirst()->toString(),
    });
    $processos = $user->hasRole('super_admin')
        ? \App\Models\Processo::query()->orderByDesc('data_abertura')->limit(6)->get()
        : $user->processos()->orderByDesc('data_abertura')->limit(6)->get();
@endphp

<div class="overflow-hidden rounded-2xl border border-[#e3e9ef] bg-white">
    <div class="relative bg-[#071c2c] px-6 pb-6 pt-7 text-white">
        <div class="pointer-events-none absolute -right-20 -top-24 h-64 w-64 rounded-full" style="background: radial-gradient(circle, rgb(219 39 119 / .45), transparent 65%)"></div>
        <div class="relative flex items-center gap-4">
            <img src="{{ filament()->getUserAvatarUrl($user) }}" alt="" class="h-16 w-16 shrink-0 rounded-full object-cover ring-2 ring-white/20" />
            <div class="min-w-0">
                <p class="truncate text-lg font-bold" style="font-family: Sora, sans-serif">{{ $user->name }}</p>
                <p class="truncate text-sm text-white/60">{{ $user->email }}</p>
            </div>
        </div>
        <div class="relative mt-4 flex flex-wrap gap-1.5">
            @forelse ($papeis as $papel)
                <span class="rounded-full bg-white/10 px-2.5 py-1 text-xs font-semibold text-pink-200">{{ $papel }}</span>
            @empty
                <span class="rounded-full bg-white/10 px-2.5 py-1 text-xs font-semibold text-white/60">Sem papel definido</span>
            @endforelse
        </div>
    </div>

    <div class="px-6 py-5">
        <p class="mb-3 text-xs font-bold uppercase tracking-wider text-gray-400">
            {{ $user->hasRole('super_admin') ? 'Processos recentes' : 'Seus processos' }}
        </p>
        @forelse ($processos as $processo)
            <a
                href="{{ \App\Filament\App\Pages\EditProcessoSeletivo::getUrl(panel: 'app', tenant: $processo) }}"
                class="-mx-2 flex items-center justify-between gap-3 rounded-lg px-2 py-2 text-sm hover:bg-gray-50"
            >
                <span class="truncate font-medium text-gray-800">{{ $processo->titulo }}</span>
                <span class="shrink-0 text-xs font-semibold text-gray-500">{{ $processo->status->value }}</span>
            </a>
        @empty
            <p class="text-sm text-gray-500">Você ainda não está na comissão de nenhum processo.</p>
        @endforelse

        <div class="mt-4 border-t border-gray-100 pt-4 text-xs text-gray-500">
            Conta criada em {{ $user->created_at?->format('d/m/Y') }}
        </div>
    </div>
</div>
