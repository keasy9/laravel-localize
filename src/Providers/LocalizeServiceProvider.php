<?php

namespace Keasy9\Localize\Providers;

use Illuminate\Support\ServiceProvider;
use Keasy9\Localize\Facades\Localize;

class LocalizeServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind('Localizе', Localize::class);

        $this->mergeConfigFrom(__DIR__ . '/../config/localize.php', 'localize');
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/localize.php' => config_path('localize.php'),
        ]);
    }
}
