<div class="flex gap-2 justify-grow grow">
    <span>{{ __('localize::messages.page') }}:</span>
    <ul class="flex gap-2 grow justify-around">
        @if ($paginator->onFirstPage())
            <li>&lsaquo;</li>
            <li>1</li>
        @else
            <li class="cursor-pointer text-sky-600"><a href="{{ $paginator->previousPageUrl() }}&perPage={{ request('perPage', 25) }}">&lsaquo;</a></li>
            <li class="cursor-pointer text-sky-600"><a href="{{ $paginator->url(1) }}&perPage={{ request('perPage', 25) }}">1</a></li>
            @if ($paginator->currentPage() > 2 && $paginator->lastPage() > 2)
                <li>...</li>
            @endif
            <li>{{ $paginator->currentPage() }}</li>
        @endif

        @if ($paginator->currentPage() < $paginator->lastPage() - 1)
            <li>...</li>
        @endif

        @if ($paginator->currentPage() !== $paginator->lastPage())
            <li class="cursor-pointer text-sky-600"><a href="{{ $paginator->url($paginator->lastPage()) }}&perPage={{ request('perPage', 25) }}">{{ $paginator->lastPage() }}</a></li>
        @endif

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