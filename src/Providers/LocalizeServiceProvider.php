<?php

namespace Keasy9\Localize\Providers;

use Illuminate\Support\ServiceProvider;
use Keasy9\Localize\Facades\Localize;

class LocalizeServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind('Localizе', Localize::class);

        $this->mergeConfigFrom(__DIR__.'/../config/localize.php', 'localize');
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'localize');
        $this->loadTranslationsFrom(__DIR__.'/../lang', 'localize');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->publishes([
            __DIR__.'/../config/localize.php' => config_path('localize.php'),
            __DIR__.'/../lang' => lang_path('vendor/localize'),
            __DIR__.'/../public' => public_path('vendor/localize'),
            __DIR__.'/../resources/views' => resource_path('views/vendor/localize'),
        ]);
    }
}
