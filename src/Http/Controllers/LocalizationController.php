<?php

namespace Keasy9\Localize\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Keasy9\Localize\Models\File;
use Illuminate\Support\Facades\File as FileFacade;

class LocalizationController extends Controller
{
    public function files(Request $request)
    {
        $files = File::getAll();

        foreach ($files as &$file) {
            $file->loadTimestamp()->loadSize();
        }

        return view('localize::files', ['files' => $files]);
    }

    public function file(Request $request, string $filename)
    {
        $page = $request->get('page', 1);
        $perPage = $request->get('perPage', 25);

        $file = new File(lang_path($filename));
        $strings = $file->getStrings($request->get('search', ''));
        $perPage = $perPage === 'all' ? $strings->count() : $perPage;

        $strings = new LengthAwarePaginator(
            $strings->forPage($page, $perPage),
            $strings->count(),
            $perPage,
            $page,
            ['path' => Paginator::resolveCurrentPath()],
        );

        return view('localize::file', ['file' => $file, 'strings' => $strings,]);
    }

    public function saveString(Request $request, string $filename)
    {
        return redirect()->back()->with(
            'saved',
            (new File(lang_path($filename)))->saveString($request->get('key', ''), $request->get('value') ?? '')->save()
        );
    }

    public function deleteString(Request $request, string $filename, string $key)
    {
        return redirect()->back()->with('deleted', (new File(lang_path($filename)))->removeString($key)->save());
    }

    public function fillFile(Request $request, string $filename)
    {
        $keys = [];

        foreach (glob(resource_path('views/**/*.blade.php')) as $file) {

            // remove all html and \n so string will become shorter and searching will be faster
            $content = str_replace("\n", '', strip_tags(file_get_contents($file)));
            preg_match_all("/__\(['\"](.*)['\"]\)/U", $content, $matches, PREG_PATTERN_ORDER);
            $keys = array_merge($keys, $matches[1]);
        }

        return redirect()->back()->with('filled', (new File(lang_path($filename)))->addKeys($keys)->save());
    }

    public function exportFile(Request $request, string $filename)
    {
        return response()->download(lang_path($filename), $filename, ['Content-Type: application/json']);
    }

    public function importFile(Request $request, string $filename)
    {
        json_decode(file_get_contents($request->file('file')));
        $isValid = json_last_error() === JSON_ERROR_NONE;

        if ($isValid) {
            $isValid = FileFacade::copy($request->file('file')->getRealPath(), lang_path($filename));
        }

        return redirect()->back()->with('imported', $isValid);
    }
}
