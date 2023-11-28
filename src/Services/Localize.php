<?php

namespace Keasy9\Localize\Services;

use Illuminate\Support\Facades\Config;

class Localize
{
    public function getLocalePrefix(): string
    {
        $prefix = request()->segment(1, '');
        if (!in_array($prefix, Config::get('localize.available_locales', []))) {
            return '';
        }

        app()->setLocale($prefix);
        return $prefix;
    }
}
