<div class="breadcrumbs -mt-2 text-sm mb-1">
    <ul>
        <li>
            <a wire:navigate href="{{ route('home') }}" class="text-gray-400 hover:text-gray-500">
                <svg class="h-5 w-5 flex-shrink-0 text-primary" viewBox="0 0 20 20" fill="currentColor"
                     aria-hidden="true">
                    <path fill-rule="evenodd"
                          d="M9.293 2.293a1 1 0 011.414 0l7 7A1 1 0 0117 11h-1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-3a1 1 0 00-1-1H9a1 1 0 00-1 1v3a1 1 0 01-1 1H5a1 1 0 01-1-1v-6H3a1 1 0 01-.707-1.707l7-7z"
                          clip-rule="evenodd"/>
                </svg>
                <span class="sr-only" aria-current="page">Principal</span>
            </a>
        </li>
        @foreach($breadcrumbs as $breadcrumb)
            <li>
                @if ($breadcrumb->url)
                    <a wire:navigate href="{{ $breadcrumb->url }}"
                       class="text-sm font-medium text-gray-500 hover:text-gray-700"
                       @if ($loop->last) aria-current="page" @endif>{!! $breadcrumb->title !!}</a>
                @else
                    <div class="text-sm font-medium text-gray-500 hover:text-gray-700"> {!! $breadcrumb->title !!} </div>
                @endif
            </li>
        @endforeach
    </ul>
</div>
