## Laravel-localize - little but usefull package for localisation.

### Installation:

 - composer require keasy9/laravel-localize
 - php artisan vendor:publish --provider='Keasy9\Localize\Providers\LocalizeServiceProvider'

### Usage:

#### Adding uti-prefix:

At first you need to add available locales in config/localize.php. By default here is two locales:

    'available_locales' => [
        'ru' => 'русский',
        'en' => 'english',
    ],

And add prefix for your app's routes:

    namespace App\Providers;

    //...
    use Keasy9\Localize\Facades\Localize;

    class RouteServiceProvider extends ServiceProvider
    {

        public function boot(): void
        {
    
            $this->routes(function () {
                //...
    
                Route::middleware('web')
                    ->prefix(Localize::getLocalePrefix()) //prefix
                    ->group(base_path('routes/web.php'));
            });
        }
    }

Or in routes/web.php:

    use Illuminate\Support\Facades\Route;
    use Keasy9\Localize\Facades\Localize;

    Route::prefix(Localize::getLocalePrefix())->group(function() {
        //...
    });

#### Localize lang/*.json files:

This package also provides a simple web interface for editing JSON language files at yourSiteRoot/localize/files/. But if you need to change this URI, you can do it through the configuration in config/localize.php:

    'uri' => 'localize',

You can also specify a middleware to access that URL or remove it to open access to all users:

    'access' => ['web','auth'],

Now it's time for you to create an amazing web application!