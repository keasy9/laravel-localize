@extends('localize::layout')

@section('content')
    <div class="flex flex-wrap justify-between items-center gap-2 border-b-4 border-sky-600">
        <p>{{ __('localize::messages.file') }}: {{ lang_path($file->basename) }}</p>
        <p>{{ __('localize::messages.lang') }}: {{ __(\Keasy9\Localize\Facades\Localize::getLangName($file->filename)) }}</p>
    </div>
    @push('toolbar')
        <div class="border border-slate-300 p-4 flex flex-wrap bg-gray-100 justify-grow gap-2">
            {{ $strings->links('localize::pagination') }}
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
            <div class="flex grow justify-grow flex-wrap gap-2">
                <input type="button" value="{{ __('localize::messages.add_translation') }}" class="border border-slate-300 px-1 grow addString">
                <button class="autofill border border-slate-300 px-1 grow">{{ __('localize::messages.autofill') }}</button>
            </div>
        </div>
    @endpush
    @stack('toolbar')
    <div>
        @foreach($strings as $key => $value)
            <form action="{{ route('localize.file.saveString', $file->basename) }}" data-is-new="0" method="post" class="flex flex-wrap odd:bg-slate-100 p-4 gap-2 md:flex-nowrap items-center fileString">
                @csrf
                <textarea placeholder="{{ __('localize::messages.empty') }}" name="key" class="border border-slate-300 w-full disabled:text-center disabled:bg-inherit disabled:border-none resize-none p-1" disabled>{{ $key }}</textarea>
                <div class="w-full text-center md:w-fit">
                    <div class="rotate-90 inline-block md:rotate-0">=></div>
                </div>
                <textarea placeholder="{{ __('localize::messages.empty') }}" name="value" class="border border-slate-300 w-full disabled:text-center disabled:bg-inherit disabled:border-none resize-none p-1" disabled>{{ $value }}</textarea>
                <div class="w-full flex justify-end md:w-fit items-center">
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
                    <span title="{{ __('localize::messages.cancel') }}" class="hidden cursor-pointer">
                        <svg fill="#000000" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="w-10">
                            <circle cx="12" cy="12" r="9" style="fill: #2ca9bc; stroke-width: 2;"></circle>
                            <line x1="15" y1="15" x2="9" y2="9" style="fill: none; stroke: #000000; stroke-linecap: round; stroke-linejoin: round; stroke-width: 2;"></line>
                            <line x1="9" y1="15" x2="15" y2="9" style="fill: none; stroke: #000000; stroke-linecap: round; stroke-linejoin: round; stroke-width: 2;"></line>
                            <circle cx="12" cy="12" r="9" style="fill: none; stroke: #000000; stroke-linecap: round; stroke-linejoin: round; stroke-width: 2;"></circle>
                        </svg>
                    </span>
                    <a title="{{ __('localize::messages.delete') }}" href="{{ route('localize.file.deleteString', [$file->basename, $key]) }}" class="cursor-pointer">
                        <svg fill="#000000" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="w-10">
                            <path d="M5,8H18a1,1,0,0,1,1,1V19a1,1,0,0,1-1,1H5a0,0,0,0,1,0,0V8A0,0,0,0,1,5,8Z" transform="translate(26 2) rotate(90)" style="fill: SteelBlue; stroke-width: 2;"></path>
                            <path d="M16,7V4a1,1,0,0,0-1-1H9A1,1,0,0,0,8,4V7" style="fill: none; stroke: #000000; stroke-linecap: round; stroke-linejoin: round; stroke-width: 2;"></path>
                            <path d="M10,11v6m4-6v6M4,7H20M18,20V7H6V20a1,1,0,0,0,1,1H17A1,1,0,0,0,18,20Z" style="fill: none; stroke: #000000; stroke-linecap: round; stroke-linejoin: round; stroke-width: 2;"></path>
                        </svg>
                    </a>
                </div>
            </form>
        @endforeach
    </div>
    <dialog id="autofillConfirmation" class="border border-slate-300 p-4">
        <div class="flex flex-wrap gap-4">
            <div class="flex flex-wrap items-center justify-around gap-2">
                <svg fill="#000000" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="w-10">
                    <rect x="3" y="3" width="18" height="18" rx="9" style="fill: SteelBlue; stroke-width: 2;"></rect>
                    <line x1="11.95" y1="16.5" x2="12.05" y2="16.5" style="fill: none; stroke: #000000; stroke-linecap: round; stroke-linejoin: round; stroke-width: 2.5;"></line>
                    <path d="M3,12a9,9,0,0,1,9-9h0a9,9,0,0,1,9,9h0a9,9,0,0,1-9,9h0a9,9,0,0,1-9-9Zm9,0V7" style="fill: none; stroke: #000000; stroke-linecap: round; stroke-linejoin: round; stroke-width: 2;"></path>
                </svg>
                <p class="text-justify">{{ __('localize::messages.autofill_confirmation') }}</p>
            </div>
            <div class="flex justify-center gap-2 grow">
                <a href="{{ route('localize.file.autofill', $file->basename) }}">
                    <button class="border border-slate-300 bg-gray-100 p-1">{{ __('localize::messages.continue') }}</button>
                </a>
                <button class="border border-slate-300 bg-gray-100 p-1">{{ __('localize::messages.cancel') }}</button>
            </div>
        </div>
    </dialog>
    @stack('toolbar')
    @push('js')
        <script>
            window.addEventListener('load', (event) => {
                (new (class {
                    itemInstance = new (class {
                        node = null;
                        key = null;
                        value = null;

                        unlock() {
                            this.key = this.node.children[1].value;
                            this.value = this.node.children[3].value;
                            this.node.children[1].disabled = false;
                            this.node.children[3].disabled = false;
                            this.node.lastElementChild.firstElementChild.classList.add('hidden');
                            this.node.lastElementChild.children[1].classList.remove('hidden');
                            this.node.lastElementChild.children[2].classList.remove('hidden');

                            return this;
                        }

                        lock() {
                            this.node.children[1].disabled = true;
                            this.node.children[3].disabled = true;
                            this.node.lastElementChild.firstElementChild.classList.remove('hidden');
                            this.node.lastElementChild.children[1].classList.add('hidden');
                            this.node.lastElementChild.children[2].classList.add('hidden');

                            return this;
                        }

                        reset() {
                            if (this.node.dataset.isNew == 0) {
                                this.node.children[1].value = this.key;
                                this.node.children[3].value = this.value;

                                return this;

                            } else {
                                this.node.remove();
                            }
                        }
                    });
                    unlockedItem = null;
                    dialog = document.getElementById('autofillConfirmation');

                    init() {
                        document.querySelectorAll('.fileString').forEach((string) => {
                            this.addEventListeners(string);
                        });

                        document.querySelectorAll('.addString').forEach((string) => {
                            string.addEventListener('click', () => {
                                if (this.unlockedItem) {
                                    this.item(this.unlockedItem).reset();
                                }
                                let lastString = document.querySelector('.file_string:last-of-type');
                                let newString = lastString.cloneNode(true);
                                this.addEventListeners(newString);
                                newString.children[1].value = newString.children[3].value = '';
                                this.item(newString).unlock();
                                lastString.after(newString);
                                newString.children[1].focus();
                                newString.dataset.isNew = 1;
                                this.unlockedItem = newString;
                            });
                        });

                        document.querySelectorAll('.autofill').forEach((btn) => {
                            btn.addEventListener('click', () => {
                                this.dialog.showModal();
                            });
                        });

                        this.dialog.lastElementChild.lastElementChild.addEventListener('click', () => {
                            this.dialog.close();
                        });
                    }

                    addEventListeners(string) {
                        string.lastElementChild.firstElementChild.addEventListener('click', (event) => {
                            if (this.unlockedItem !== null) {
                                this.item(this.unlockedItem).lock();
                            }
                            this.item(string).unlock();
                            this.unlockedItem = string;
                        });
                        string.lastElementChild.children[2].addEventListener('click', (event) => {
                            this.item(string).lock().reset();
                            this.unlockedItem = null;
                        });
                    }

                    item(string) {
                        this.itemInstance.node = string;
                        return this.itemInstance;
                    }
                })).init();
            });
        </script>
    @endpush
@endsection