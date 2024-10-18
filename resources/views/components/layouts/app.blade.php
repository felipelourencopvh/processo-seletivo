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
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('styles')
        @yield('styles')
    </head>
    <body id="main" class="min-h-screen bg-base-200/50 font-sans text-base antialiased" x-data>
        <x-layouts.parts.header />

        {{-- MAIN --}}
        <x-layouts.parts.main full-width>
            {{-- SIDEBAR --}}
            <x-slot:sidebar
                drawer="main-drawer"
                class="dark:border-r-coal-100 border-r border-r-gray-200 bg-base-100 lg:bg-inherit"
            >
                {{-- MENU --}}
                <x-layouts.parts.menu />
            </x-slot>

            {{-- The `$slot` goes here --}}
            <x-slot:content>
                {{ $slot }}
            </x-slot>
        </x-layouts.parts.main>
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
