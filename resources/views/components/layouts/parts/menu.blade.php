<?php

//use RyanChandler\FilamentNavigation\Models\Navigation;

$menuServicos = ['items' => []];
?>

<x-menu border border-dashed activate-by-route>
    <x-menu-item
        title="Pesquisar"
        @click.stop="$dispatch('mary-search-open')"
        icon="o-magnifying-glass"
        class="max-h-screen-2xl mh-auto"
        badge="Win + G"
    />
    <x-menu-separator />
    <x-menu-item :title="'Principal'" :link="'/'" />

    @foreach ($menuServicos['items'] as $menu)
        @if (! empty($menu['children']))
            <x-layouts.parts.menu.sub-menu :title="$menu['label']" :submenu="$menu['children']" />
        @else
            <x-layouts.parts.menu.menu
                :title="$menu['label']"
                :page="$menu['data']['url']"
                :new-tab="$menu['type'] === 'external-link'"
            />
        @endif
    @endforeach
</x-menu>
