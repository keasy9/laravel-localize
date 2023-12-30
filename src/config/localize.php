<?php

use App\Models\Pose;

return [
    'available_locales' => [
        'ru' => 'русский',
        'en' => 'english',
    ],

    'default_locale' => 'en',

    'translate_default_locale' => false,

    'uri' => 'localize',

    'access' => ['web', 'auth'],

    'translated_models' => [
        Pose::class,
    ],
];
