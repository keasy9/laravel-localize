<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>{{ Config::get('app.name') }} | @yield('title', __('Локализация сайта'))</title>
        @stack('js')
        <link rel="stylesheet" href="{{ asset("/vendor/localize/css/index.min.css") }}">
    </head>
    <body class="pt-14">
        <header class="bg-sky-600 text-white flex p-4 justify-between fixed w-full top-0">
            <b>{{ __('localize::messages.site_localization') }}</b>
            <div>
                <a href="{{ route('localize.files') }}" class="underline underline-offset-8 hover:underline-offset-1 font-bold transition-all">{{ __('localize::messages.files') }}</a>
            </div>
        </header>
        <section class="center p-4 space-y-4 lg:w-3/5 mx-auto">
            @if(session()->has('imported'))
                <div class="border border-slate-300 p-4 bg-green-200 cursor-pointer" onclick="this.remove()">{{ __('localize::messages.file_imported') }}</div>
            @elseif(session()->has('saved'))
                <div class="border border-slate-300 p-4 bg-green-200 cursor-pointer" onclick="this.remove()">{{ __('localize::messages.string_saved') }}</div>
            @elseif(session()->has('deleted'))
                <div class="border border-slate-300 p-4 bg-green-200 cursor-pointer" onclick="this.remove()">{{ __('localize::messages.string_deleted') }}</div>
            @elseif(session()->has('filled'))
                <div class="border border-slate-300 p-4 bg-green-200 cursor-pointer" onclick="this.remove()">{{ __('localize::messages.file_filled') }}</div>
            @endif
            @yield('content')
        </section>
    </body>
</html>
