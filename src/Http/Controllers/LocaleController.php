<?php

namespace Keasy9\Localize\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;
use Keasy9\Localize\Models\Translation;

class LocaleController extends Controller
{
    public function index(Request $request): View
    {
        $locales = config('localize.available_locales');
        $defaultLocale = config('localize.default_locale');

        $translations = Translation::get()->groupBy('locale');
        foreach ($locales as $locale => &$lang) {
            $lang = ['lang' => $lang];
            $lang['file']['filename'] = lang_path("{$locale}.json");
            $lang['file']['size'] = $size = File::size($lang['file']['filename']);
            $lang['file']['time'] = Carbon::createFromTimestamp(File::lastModified($lang['file']['filename']))->format('Y.m.d, H:i');
            $lang['isDefault'] = $locale === $defaultLocale;

            $sizeUnits = ['b', 'kb', 'mb', 'gb', 'tb'];
            $sizeUnit = 0;
            while ($size >= 1024 && isset($sizeUnits[$sizeUnit+1])) {
                $size = round($size/1024, 2);
                $sizeUnit++;
            }
            $lang['file']['size'] = "{$size} " . $sizeUnits[$sizeUnit];

            $lang['models'] = [];

            if (isset($translations[$locale])) {
                $t_locale = $translations[$locale]->groupBy('model_type');

                foreach (config('localize.translated_models', []) as $model) {
                    $t = $t_locale[$model]->groupBy('model_id');
                    $translatedFieldsCount = count($model::$translated);
                    $lang['models'][$model] = [
                        'count' => $model::count(),
                        'fullyTranslated' => $t->filter(function($translations) use ($translatedFieldsCount) { return $translations->count() == $translatedFieldsCount; })->count(),
                        'partiallyTranslated' => $t->filter(function($translations) use ($translatedFieldsCount) { return $translations->count() < $translatedFieldsCount; })->count(),
                    ];
                }
            } else {
                foreach (config('localize.translated_models', []) as $model) {
                    $lang['models'][$model] = [
                        'count' => $model::count(),
                        'fullyTranslated' => 0,
                        'partiallyTranslated' => 0,
                    ];
                }
            }
        }

        return view('localize::locales', [
            'locales' => $locales,
            'translateDefaultLocale' => config('localize.translate_default_locale')
        ]);
    }
}
