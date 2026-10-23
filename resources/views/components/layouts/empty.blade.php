<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <title>{{ isset($title) ? $title.' - '.config('app.name') : config('app.name') }}</title>

        <link rel="icon" type="image/svg+xml" href="{{ asset('marca/recrutamento-favicon.svg') }}" />
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link
            rel="stylesheet"
            href="https://fonts.googleapis.com/css2?family=Inter+Tight:wght@400;500;600;700&family=Sora:wght@500;600;700;800&display=swap"
        />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-[#071C2C] font-sans antialiased">
        <div class="pointer-events-none fixed inset-0 overflow-hidden" aria-hidden="true">
            <div class="brilho-rosa -right-48 -top-64 h-[820px] w-[820px]"></div>
            <div class="brilho-laranja -bottom-96 -left-52 h-[600px] w-[600px]"></div>
        </div>
        <main class="relative mx-auto w-full">
            <div class="mx-auto w-full p-5 lg:px-10 lg:py-8">
                <a wire:navigate href="{{ route('home') }}" class="mx-auto mt-4 flex w-fit" aria-label="STX Gov · Recrutamento — página inicial">
                    <x-marca claro />
                </a>
                {{ $slot }}
            </div>
        </main>
        {{-- Toast --}}
        <x-toast />
    </body>
</html>
