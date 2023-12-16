@extends('localize::layout')

@section('content')
    @foreach($locales as $locale => $lang)
        @php
            $disabled = $lang['isDefault'] && !$translateDefaultLocale;
        @endphp
        <div @class(['border-2', 'border-slate-300', 'p-4', 'bg-gray-100', 'space-y-4', 'opacity-60' => $disabled])>
            <h2 class="text-xl">
                {{ mb_convert_case(__('localize::messages.' . \Keasy9\Localize\Facades\Localize::getLangName($locale)), MB_CASE_TITLE) }}
                ({{ $locale }})
                @if($lang['isDefault'])
                    - {{ __('localize::messages.default_locale') }}
                @endif
            </h2>
            <div class="flex flex-wrap justify-between gap-4">
                <div class="w-full md:w-auto">
                    <p>
                        <h4 class="text-lg">{{ __('localize::messages.file') }}:</h4>
                        <span>{{ $lang['file']['filename'] }}</span>
                    </p>
                    <p>
                        <span>{{ __('localize::messages.size') }}:</span>
                        <span>{{ $lang['file']['size'] }}</span>
                    </p>
                    <p>
                        <span>{{ __('localize::messages.changed') }}:</span>
                        <span>{{ $lang['file']['time'] }}</span>
                    </p>
                    <div class="flex justify-between">
                        @if($disabled)
                            <p class="underline underline-offset-8 text-slate-600">{{ __('localize::messages.edit') }}</p>
                            <p class="underline underline-offset-8 text-slate-600">{{ __('localize::messages.export') }}</p>
                            <p class="underline underline-offset-8 text-slate-600">{{ __('localize::messages.import') }}</p>
                        @else
                            <a href="{{ route('localize.file.strings.index', $locale) }}" class="underline underline-offset-8 hover:underline-offset-1 transition-all text-sky-600">{{ __('localize::messages.edit') }}</a>
                            <a href="{{ route('localize.file.export', $locale) }}" target="_blank" class="underline underline-offset-8 hover:underline-offset-1 transition-all text-sky-600">{{ __('localize::messages.export') }}</a>
                            <form action="{{ route('localize.file.import', $locale) }}" method="post" enctype="multipart/form-data" class="inline">
                                @csrf
                                <input type="file" accept="application/json" name="file" onchange="this.form.submit()" class="hidden">
                                <button type="button" class="underline underline-offset-8 hover:underline-offset-1 transition-all text-sky-600" onclick="this.previousElementSibling.click()">
                                    {{ __('localize::messages.import') }}
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
                <div class="w-full">
                    <h4 class="text-lg">{{ __('localize::messages.models') }}:</h4>
                    <div class="flex justify-grow flex-wrap gap-2 md:gap-6 pt-2">
                        @foreach($lang['models'] as $model => $statistics)
                            <div class="grow space-y-2">
                                <div class="flex justify-between">
                                    <p>{{ $model }}</p>
                                    @if($disabled)
                                        <p class="underline underline-offset-8 text-slate-600">{{ __('localize::messages.to_translate') }}</p>
                                    @else
                                        <a href="{{ route('localize.models.index', [$locale, $model]) }}" class="underline underline-offset-8 hover:underline-offset-1 transition-all text-sky-600">{{ __('localize::messages.to_translate') }}</a>
                                    @endif
                                </div>
                                <table class="w-full border-separate border-spacing-x-2">
                                    <tr>
                                        <td>{{ __('localize::messages.fully_translated') }}:</td>
                                        <td>{{ $statistics['fullyTranslated'] }}/{{ $statistics['count'] }}</td>
                                        <td>-</td>
                                        <td>{{ round($statistics['fullyTranslated'] / $statistics['count'] * 100, 2) }}%</td>
                                    </tr>
                                    <tr>
                                        <td>{{ __('localize::messages.partially_translated') }}:</td>
                                        <td>{{ $statistics['partiallyTranslated'] }}/{{ $statistics['count'] }}</td>
                                        <td>-</td>
                                        <td>{{ round($statistics['partiallyTranslated'] / $statistics['count'] * 100, 2) }}%</td>
                                    </tr>
                                </table>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection