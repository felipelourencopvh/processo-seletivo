<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <title>{{ isset($title) ? $title.' - '.config('app.name') : config('app.name') }}</title>
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
        {{-- PhotoSwipe --}}
        <script src="https://cdn.jsdelivr.net/npm/photoswipe@5.4.3/dist/umd/photoswipe.umd.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/photoswipe@5.4.3/dist/umd/photoswipe-lightbox.umd.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/photoswipe@5.4.3/dist/photoswipe.min.css" rel="stylesheet" />
        {{-- EasyMDE --}}
        <link rel="stylesheet" href="https://unpkg.com/easymde/dist/easymde.min.css" />
        <script src="https://unpkg.com/easymde/dist/easymde.min.js"></script>
        <!-- Fonte Rawline-->
        <link
            rel="stylesheet"
            href="https://cdngovbr-ds.estaleiro.serpro.gov.br/design-system/fonts/rawline/css/rawline.css"
        />
        <!-- Fonte Raleway-->
        <link
            rel="stylesheet"
            href="https://fonts.googleapis.com/css?family=Raleway:300,400,500,600,700,800,900&amp;display=swap"
        />
        <!-- Fontawesome-->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('styles')
        @yield('styles')
    </head>
    <body id="main" class="min-h-screen font-rawline text-base antialiased">
        <x-main>
            <x-slot:sidebar drawer="main-drawer" collapsible class="bg-base-100">
                <x-layouts.parts.menu />
            </x-slot>

            {{-- The `$slot` goes here --}}
            <x-slot:content class="relative">
                {{ $slot }}
            </x-slot>
        </x-main>
        {{-- MAIN --}}
        <x-layouts.parts.footer />
        {{-- TOAST area --}}
        <x-toast />

        @stack('scripts')
        <script
            src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
            crossorigin=""
        ></script>
        <script src="https://cdn.jsdelivr.net/npm/leaflet.locatecontrol@0.81.0/dist/L.Control.Locate.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@gnatih/leaflet.legend@1.0.1/src/leaflet.legend.min.js"></script>
    </body>
</html>
