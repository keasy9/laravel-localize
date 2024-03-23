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

    public function localizeUrl(string $url, ?string $locale): string
    {
        $locale ??= app()->getLocale();
        $url = parse_url($url);

        $url['path'] ??= '';
        $path = explode('/', $url['path'] ?? '');
        $path = array_values(array_filter($path));
        if (!empty($url['path']) && in_array($path[0], array_keys(config('localize.available_locales')))) {
            array_shift($path);
        }
        if ($locale !== config('localize.default_locale')) {
            array_unshift($path, $locale);
        }
        $path = implode('/', $path);
        if (empty($url['path']) || mb_substr($url['path'], 0, 1) === '/') {
            $path = "/{$path}";
        }

        $result = empty($url['scheme']) ? '' : "{$url['scheme']}://";
        if (!empty($url['user'])) {
            $result .= $url['user'] . (empty($url['pass']) ? '@' : ":{$url['pass']}@");
        }
        $result .= $url['host'] ?? '';
        $result .= empty($url['port']) ? '' : ":{$url['port']}";
        $result .= $path;
        $result .= empty($url['query']) ? '' : "?{$url['query']}";
        $result .= empty($url['fragment']) ? '' : "#{$url['fragment']}";
        return $result;
    }
}
