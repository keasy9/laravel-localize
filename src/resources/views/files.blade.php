@extends('localize::layout')

@section('content')
    @foreach($files as $file)
        <div class="border border-slate-300 p-4 bg-gray-100 space-y-4 flex flex-wrap justify-between md:space-y-0">
            <div>
                <p>{{ lang_path($file->basename) }}</p>
                <p>{{ mb_convert_case(__('localize::messages.' . \Keasy9\Localize\Facades\Localize::getLangName($file->filename)), MB_CASE_TITLE) }}</p>
                <p>{{ __('localize::messages.size') }}: {{ $file->size }}</p>
                <p>{{ __('localize::messages.changed') }}: {{ $file->lastModify }}</p>
            </div>
            <div class="flex w-full md:w-fit md:flex-col items-end justify-between">
                <a href="{{ route('localize.file', $file->basename) }}" class="underline underline-offset-8 hover:underline-offset-1 transition-all text-sky-600">{{ __('localize::messages.edit') }}</a>
                <a href="{{ route('localize.file.export', $file->basename) }}" target="_blank" class="underline underline-offset-8 hover:underline-offset-1 transition-all text-sky-600">{{ __('localize::messages.export') }}</a>
                <form action="{{ route('localize.file.import', $file->basename) }}" method="post" enctype="multipart/form-data" class="inline">
                    @csrf
                    <input type="file" accept="application/json" name="file" onchange="this.form.submit()" class="hidden">
                    <button type="button" class="underline underline-offset-8 hover:underline-offset-1 transition-all text-sky-600" onclick="this.previousElementSibling.click()">
                        {{ __('localize::messages.import') }}
                    </button>
                </form>
            </div>
        </div>
    @endforeach
@endsection
