<?php

namespace Keasy9\Localize\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class FileStringController extends Controller
{
    public function index(Request $request, string $locale): View
    {
        $page = $request->get('page', 1);
        $perPage = $request->get('perPage', 25);

        $file = [
            'filename' => "{$locale}.json",
            'basename' => $locale,
        ];
        $file['content'] = collect(File::json(lang_path("{$locale}.json")));
        $perPage = $perPage === 'all' ? $file['content']->count() : $perPage;

        if ($request->filled('search')) {
            $file['content'] = $file['content']->filter(function(string $key, string $value) use ($request) {
                return str_contains($key, $request->get('search')) || str_contains($value, $request->get('search'));
            });
        }

        $file['content'] = new LengthAwarePaginator(
            $file['content']->forPage($page, $perPage),
            $file['content']->count(),
            $perPage,
            $page,
            ['path' => Paginator::resolveCurrentPath()],
        );

        return view('localize::file', ['file' => $file]);
    }

    public function save(Request $request, string $locale): RedirectResponse
    {
        $filename = lang_path("{$locale}.json");
        $fileContent = File::json($filename);
        $fileContent[$request->input('key', '')] = $request->input('value') ?? '';

        return redirect()->back()->with(
            'saved',
            (bool)File::put($filename, json_encode($fileContent, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))
        );
    }

    public function destroy(Request $request, string $locale, string $key): RedirectResponse
    {
        $filename = lang_path("{$locale}.json");
        $fileContent = File::json($filename);
        unset($fileContent[$key]);

        return redirect()->back()->with(
            'deleted',
            (bool)File::put($filename, json_encode($fileContent, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))
        );
    }

    public function autofill(Request $request, string $locale): RedirectResponse
    {
        $keys = [];

        foreach (glob(resource_path('views/**/*.blade.php')) as $file) {

            // remove all html and \n so string will become shorter and searching will be faster
            $content = str_replace("\n", '', strip_tags(file_get_contents($file)));
            preg_match_all("/__\(['\"](.*)['\"]\)/U", $content, $matches, PREG_PATTERN_ORDER);
            $keys = array_merge($keys, $matches[1]);
        }

        $filename = lang_path("{$locale}.json");
        $fileContent = File::json($filename);
        $fileContent = collect(array_fill_keys($keys, ''))->merge($fileContent);

        return redirect()->back()->with(
            'filled',
            (bool)File::put($filename, json_encode($fileContent, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))
        );
    }

    public function export(Request $request, string $locale): BinaryFileResponse
    {
        $filename = "{$locale}.json";
        return response()->download(lang_path($filename), $filename, ['Content-Type: application/json']);
    }

    public function import(Request $request, string $locale): RedirectResponse
    {
        json_decode(file_get_contents($request->file('file')));
        $isValid = json_last_error() === JSON_ERROR_NONE;

        if (!$isValid) {
            return redirect()->back()->with('imported', $isValid);
        }

        return redirect()->back()->with('imported', File::copy($request->file('file')->getRealPath(), lang_path("{$locale}.json")));
    }
}
