## Laravel-localize - package for adding url-prefix based on the current app locale.

#### Installation:

 - composer require keasy9/laravel-localize
 - php artisan vendor:publish --provider='Keasy9\Localize\Providers\LocalizeServiceProvider'

#### Usage:

At first you need to add available locales in config/localize.php. By default here is two locales:

    return [
        'available_locales' => ['ru', 'en',]
    ];

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

    Route::prefix(Localize::getLocalePrefix())->group(function() {
        //...
    });

Now it's time for you to create an amazing web application!