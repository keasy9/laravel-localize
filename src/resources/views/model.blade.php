@extends('localize::layout')

@section('content')
    <div class="flex flex-wrap justify-between items-center gap-2 border-b-4 border-sky-600">
        <p>{{ __('localize::messages.model') }}: {{ $model['name'] }}</p>
        <p>{{ __('localize::messages.lang') }}: {{ mb_convert_case(__('localize::messages.' . \Keasy9\Localize\Facades\Localize::getLangName($locale)), MB_CASE_TITLE) }}</p>
    </div>
    @push('toolbar')
        <div class="border border-slate-300 p-4 flex flex-wrap bg-gray-100 justify-grow gap-2">
            {{ $model['list']->onEachSide(0)->links('localize::pagination') }}
            <div class="flex justify-grow grow">
                <a href="{{ url()->current() }}?perPage={{ request('perPage', 25) }}">
                    <input type="button" value="{{ __('localize::messages.reset') }}" class="border border-slate-300 px-1">
                </a>
                <form class="flex grow justify-grow">
                    <input type="hidden" name="page" value="{{ request('page', 1) }}">
                    <input type="hidden" name="perPage" value="{{ request('perPage', 25) }}">
                    <input type="text" name="search" value="{{ request('search') }}" class="border border-slate-300 grow">
                    <input type="submit" value="{{ __('localize::messages.search') }}" class="border border-slate-300 px-1">
                </form>
            </div>
        </div>
    @endpush
    @stack('toolbar')
    <div class="space-y-4">
        @foreach($model['list'] as $model)
            @php($t = $model->translations->groupBy('model_field'))
            <form
                method="post"
                action="{{ route('localize.models.save', [$locale, $model::class, $model->id]) }}"
                class="p-4 border border-slate-300 bg-gray-100 model"
            >
                @csrf
                <div class="flex justify-between">
                    <h3 class="mb-2 text-lg">{{ $model::class }}#{{ $model->id }}</h3>
                    <div class="flex justify-end md:w-fit items-center">
                        <span title="{{ __('localize::messages.edit') }}" class="cursor-pointer">
                            <svg fill="#000000" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="w-10">
                                <path d="M20,8.24,7.24,21H3V16.76L15.76,4A3,3,0,0,1,20,4h0A3,3,0,0,1,20,8.24Z" style="fill: SteelBlue; stroke-width: 2;"></path>
                                <path d="M20,8.24,7.24,21H3V16.76L15.76,4A3,3,0,0,1,20,4h0A3,3,0,0,1,20,8.24Z" style="fill: none; stroke: #000000; stroke-linecap: round; stroke-linejoin: round; stroke-width: 2;"></path>
                            </svg>
                        </span>
                        <button title="{{ __('localize::messages.save') }}" type="submit" class="hidden cursor-pointer">
                            <svg fill="#000000" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="w-10">
                                <rect x="3" y="3" width="18" height="18" rx="9" style="fill: SteelBlue; stroke-width: 2;"></rect>
                                <polyline points="8 11.5 11 14.5 16 9.5" style="fill: none; stroke: #000000; stroke-linecap: round; stroke-linejoin: round; stroke-width: 2;"></polyline>
                                <rect x="3" y="3" width="18" height="18" rx="9" style="fill: none; stroke: #000000; stroke-linecap: round; stroke-linejoin: round; stroke-width: 2;"></rect>
                            </svg>
                        </button>
                        <button title="{{ __('localize::messages.cancel') }}" type="reset" class="hidden cursor-pointer">
                            <svg fill="#000000" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="w-10">
                                <circle cx="12" cy="12" r="9" style="fill: #2ca9bc; stroke-width: 2;"></circle>
                                <line x1="15" y1="15" x2="9" y2="9" style="fill: none; stroke: #000000; stroke-linecap: round; stroke-linejoin: round; stroke-width: 2;"></line>
                                <line x1="9" y1="15" x2="15" y2="9" style="fill: none; stroke: #000000; stroke-linecap: round; stroke-linejoin: round; stroke-width: 2;"></line>
                                <circle cx="12" cy="12" r="9" style="fill: none; stroke: #000000; stroke-linecap: round; stroke-linejoin: round; stroke-width: 2;"></circle>
                            </svg>
                        </button>
                    </div>
                </div>
                <div>
                    @foreach($model::$translated as $attribute)
                        <div class="last:border-b border-t p-4 flex flex-wrap justify-center md:flex-nowrap items-stretch gap-2">
                            <p class="text-justify [text-align-last:center] max-h-28 overflow-y-auto p-1 md:w-1/2">{{ $model->$attribute }}</p>
                            <div class="w-full text-center md:w-fit">
                                <div class="rotate-90 inline-block md:rotate-0">=></div>
                            </div>
                            <textarea
                                placeholder="{{ __('localize::messages.empty') }}"
                                name="{{ $attribute }}"
                                class="border border-slate-300 w-full text-justify [text-align-last:center] disabled:bg-inherit disabled:border-none resize-none p-1 h-28 md:w-1/2"
                                disabled
                            >{{ empty($t[$attribute]) ? '' : $t[$attribute][0]->translation }}</textarea>
                        </div>
                    @endforeach
                </div>
            </form>
        @endforeach
    </div>
    @stack('toolbar')
    @push('js')
        <script>
            window.addEventListener('load', (event) => {
                (new (class {
                    itemInstance = new (class {
                        node = null;

                        unlock() {
                            for (const child of this.node.children[2].children) {
                                child.lastElementChild.disabled = false;
                            }

                            this.node.children[1].lastElementChild.firstElementChild.classList.add('hidden');
                            this.node.children[1].lastElementChild.children[1].classList.remove('hidden');
                            this.node.children[1].lastElementChild.children[2].classList.remove('hidden');

                            return this;
                        }

                        lock() {
                            for (const child of this.node.children[2].children) {
                                child.lastElementChild.disabled = true;
                            }

                            this.node.children[1].lastElementChild.firstElementChild.classList.remove('hidden');
                            this.node.children[1].lastElementChild.children[1].classList.add('hidden');
                            this.node.children[1].lastElementChild.children[2].classList.add('hidden');

                            return this;
                        }
                    });
                    unlockedItem = null;

                    init() {
                        document.querySelectorAll('.model').forEach((model) => {
                            model.children[1].lastElementChild.firstElementChild.addEventListener('click', (event) => {
                                if (this.unlockedItem !== null) {
                                    this.item(this.unlockedItem).lock();
                                }
                                this.item(model).unlock();
                                this.unlockedItem = model;
                            });
                            model.children[1].lastElementChild.lastElementChild.addEventListener('click', (event) => {
                                this.item(model).lock();
                                this.unlockedItem = null;
                            });
                        });
                    }

                    item(model) {
                        this.itemInstance.node = model;
                        return this.itemInstance;
                    }
                })).init();
            });
        </script>
    @endpush
@endsection