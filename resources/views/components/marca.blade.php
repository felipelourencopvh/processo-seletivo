@props(['claro' => false, 'sub' => 'Recrutamento'])
{{-- Marca do ecossistema STX Gov: selo do Recrutamento (fonte: sgpm/docs/marca) + "STX Gov" com o Gov em laranja. --}}
<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-2.5 whitespace-nowrap']) }}>
    <img src="{{ asset('marca/recrutamento.svg') }}" alt="" class="h-9 w-9" />
    <span class="flex flex-col leading-none">
        <b @class(['font-display text-[19px] font-extrabold', 'text-white' => $claro, 'text-[#071c2c]' => ! $claro])>
            STX <span class="text-[#f36f21]">Gov</span>
        </b>
        @if ($sub)
            <small @class(['mt-0.5 text-xs font-semibold', 'text-rosa-300' => $claro, 'text-rosa-600' => ! $claro])>{{ $sub }}</small>
        @endif
    </span>
</span>
