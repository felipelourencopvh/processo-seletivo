@php
    if (! isset($scrollTo)) {
        $scrollTo = 'body';
    }

    $scrollIntoViewJsSnippet = ($scrollTo !== false)
        ? <<<JS
           (\$el.closest('{$scrollTo}') || document.querySelector('{$scrollTo}')).scrollIntoView()
        JS
        : '';
    /**
 * @var $paginator \Illuminate\Pagination\LengthAwarePaginator
 */
@endphp

<div>
    @if ($paginator->hasPages())
        <nav class="flex items-center justify-center gap-1.5" aria-label="paginação">
            @if ($paginator->onFirstPage())
                <span class="grid h-10 w-10 place-items-center rounded-[10px] text-[#9fb2c2]" aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                    <x-mary-icon name="o-chevron-left" class="h-5 w-5" />
                </span>
            @else
                <button
                    type="button"
                    wire:click="previousPage('{{ $paginator->getPageName() }}')"
                    x-on:click="{{ $scrollIntoViewJsSnippet }}"
                    dusk="previousPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.after"
                    class="grid h-10 w-10 place-items-center rounded-[10px] border border-[#e3e9ef] bg-white hover:border-rosa-300"
                    aria-label="{{ __('pagination.previous') }}"
                >
                    <x-mary-icon name="o-chevron-left" class="h-5 w-5" />
                </button>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="px-2 text-sm text-[#7b8b99]" aria-disabled="true">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        <span wire:key="paginator-{{ $paginator->getPageName() }}-page{{ $page }}">
                            @if ($page == $paginator->currentPage())
                                <span aria-current="page" class="grid h-10 min-w-10 place-items-center rounded-[10px] bg-rosa-600 px-3 text-sm font-bold text-white">
                                    {{ $page }}
                                </span>
                            @else
                                <button
                                    type="button"
                                    wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')"
                                    x-on:click="{{ $scrollIntoViewJsSnippet }}"
                                    class="grid h-10 min-w-10 place-items-center rounded-[10px] border border-[#e3e9ef] bg-white px-3 text-sm font-semibold hover:border-rosa-300"
                                    aria-label="{{ __('Go to page :page', ['page' => $page]) }}"
                                >
                                    {{ $page }}
                                </button>
                            @endif
                        </span>
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <button
                    type="button"
                    wire:click="nextPage('{{ $paginator->getPageName() }}')"
                    x-on:click="{{ $scrollIntoViewJsSnippet }}"
                    dusk="nextPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.after"
                    class="grid h-10 w-10 place-items-center rounded-[10px] border border-[#e3e9ef] bg-white hover:border-rosa-300"
                    aria-label="{{ __('pagination.next') }}"
                >
                    <x-mary-icon name="o-chevron-right" class="h-5 w-5" />
                </button>
            @else
                <span class="grid h-10 w-10 place-items-center rounded-[10px] text-[#9fb2c2]" aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                    <x-mary-icon name="o-chevron-right" class="h-5 w-5" />
                </span>
            @endif
        </nav>
    @endif
</div>
