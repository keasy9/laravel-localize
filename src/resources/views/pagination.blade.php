<div class="flex gap-2 justify-grow grow">
    <span>{{ __('localize::messages.page') }}:</span>
    <ul class="flex gap-2 grow justify-around">
        @if ($paginator->onFirstPage())
            <li>&lsaquo;</li>
        @else
            <li class="cursor-pointer text-sky-600"><a href="{{ $paginator->previousPageUrl() }}&perPage={{ request('perPage', 25) }}">&lsaquo;</a></li>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <li>{{ $element }}</li>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li>{{ $page }}</li>
                    @else
                        <li class="cursor-pointer text-sky-600"><a href="{{ $url }}&perPage={{ request('perPage', 25) }}">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <li class="cursor-pointer text-sky-600"><a href="{{ $paginator->nextPageUrl() }}&perPage={{ request('perPage', 25) }}">&rsaquo;</a></li>
        @else
            <li>&rsaquo;</li>
        @endif
    </ul>
    <span>{{ __('localize::messages.per_page') }}:</span>
    <select name="perPage" onchange="document.location.href = `{{ url()->current() }}?perPage=${this.value}`" class="bg-white px-1 border border-slate-300">
        <option value="25" @selected($paginator->perPage() == 25)>25</option>
        <option value="50" @selected($paginator->perPage() == 50)>50</option>
        <option value="100" @selected($paginator->perPage() == 100)>100</option>
        <option value="500" @selected($paginator->perPage() == 500)>500</option>
        <option value="all" @selected(request('perPage') === 'all')>{{ __('localize::messages.all') }}</option>
    </select>
</div>