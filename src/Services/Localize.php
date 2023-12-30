<?php

namespace Keasy9\Localize\Services;

use Illuminate\Support\Facades\Config;

class Localize
{
    public function getLocalePrefix(): string
    {
        $prefix = request()->segment(1, '');
        if (! in_array($prefix, array_keys(Config::get('localize.available_locales', [])))) {
            app()->setLocale(config('localize.default_locale'));

            return '';
        }

        app()->setLocale($prefix);

        return $prefix;
    }

    public function getLangName(?string $locale): ?string
    {
        $locale ??= app()->getLocale();

        return Config::get("localize.available_locales.{$locale}");
    }
}
