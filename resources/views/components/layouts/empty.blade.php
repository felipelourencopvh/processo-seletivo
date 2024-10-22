<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <title>{{ isset($title) ? $title.' - '.config('app.name') : config('app.name') }}</title>

        <link rel="shortcut icon" type="image/x-icon" href="{{ asset('/favicon.ico') }}" />
        <link rel="mask-icon" href="{{ asset('/favicon.ico') }}" color="#ff2d20" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-[#0C326F] font-sans antialiased">
        <main class="mx-auto w-full">
            <div class="mx-auto w-full p-5 lg:px-10 lg:py-5">
                {{ $slot }}
            </div>
        </main>
        {{-- Toast --}}
        <x-toast />
    </body>
</html>
