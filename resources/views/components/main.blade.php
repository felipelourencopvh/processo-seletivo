<div class="flex flex-1 flex-col">
    <x-layouts.parts.header />
    <main @class(['mx-auto w-full flex-1', 'max-w-[1240px]' => ! $fullWidth])>
        <div
            class="drawer"
            x-data="{
                open: false,
                close() {
                    this.open = false
                    $refs.checkbox.checked = false
                },
            }"
            @keydown.window.escape="close()"
        >
            <input
                id="{{ $sidebar?->attributes['drawer'] }}"
                x-model="open"
                x-ref="checkbox"
                type="checkbox"
                class="drawer-toggle"
            />
            <div {{ $content->attributes->class(['drawer-content mx-auto w-full px-4 pb-16 pt-8 lg:px-8']) }}>
                <!-- MAIN CONTENT -->
                {{ $content }}
            </div>

            <!-- SIDEBAR -->
            @if ($sidebar)
                <div class="drawer-side z-[3000]">
                    <!-- Overlay effect , click outside -->
                    <label for="{{ $sidebar?->attributes['drawer'] }}" class="drawer-overlay"></label>
                    <!-- Content -->
                    <x-mary-card
                        wire:key="drawer-card"
                        class="min-h-screen w-11/12 rounded-none px-8 lg:w-1/4"
                        separator
                    >
                        <x-slot:title>
                            <div class="flex items-center gap-2">
                                <x-marca />
                            </div>
                        </x-slot>
                        <x-slot:menu>
                            <x-mary-button icon="o-x-mark" class="btn-ghost btn-sm text-primary" @click="close()" />
                        </x-slot>

                        {{ $sidebar }}
                    </x-mary-card>
                </div>
            @endif

            <!-- END SIDEBAR-->
        </div>
    </main>
</div>
<!-- FOOTER -->
@if ($footer)
    <footer {{ $footer?->attributes->class(['mx-auto w-full', 'max-w-screen-2xl' => ! $fullWidth]) }}>
        {{ $footer }}
    </footer>
@endif
