@props(['sub' => 'Recrutamento'])
{{-- Marca do ecossistema STX Gov: selo navy com "Gov" em laranja + o nome do sistema em índigo. --}}
<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-2 whitespace-nowrap']) }}>
    <span class="inline-flex items-center rounded-lg bg-[#071C2C] px-2.5 py-1 text-xl font-extrabold leading-none tracking-tight text-white">
        STX<span class="text-[#F36F21]">Gov</span>
    </span>
    @if ($sub)
        <span class="text-base font-semibold text-[#4338ca]">{{ $sub }}</span>
    @endif
</span>
