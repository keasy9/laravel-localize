## Laravel-localize - little but usefull package for localisation.

### Installation:

 - composer require keasy9/laravel-localize
 - php artisan vendor:publish --provider='Keasy9\Localize\Providers\LocalizeServiceProvider'
 - php artisan migrate

### Usage:

#### Adding uri-prefix:

At first you need to add available locales in config/localize.php. By default here is two locales:

    'available_locales' => [
        'ru' => 'русский',
        'en' => 'english',
    ],

And add prefix for your app's routes:

    namespace App\Providers;

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

#### Localize model fields:

Firstly you need run command ```php artisan migrate``` that creates table "translations" in your DB.
Secondly all models that need translation must use trait and have property:

    namespace App\Models;
    
    use Illuminate\Database\Eloquent\Model;
    use Keasy9\Localize\Traits\HasTranslations;
    
    class Post extends Model
    {
        use HasTranslations;
    
        // the array must include all fields that can be translated
        public static array $translated = [
            'title',
            'text',
        ];
    
    }

so when you try to get a model attribute you will get a translated version of it:

    {{ $post->title }} {{-- will returns title translated to current app locale --}}

Also you can translate all attributes:

    $post->translate();

but be carefully. If you save translated model, fields in your database will be translated:

    use App/Models/Post;

    $post->translate()->save();
    dd(Post->find($post->id)->title); //will dump translated title

Translate all attributes for all models in collection:

    $posts->translate();

#### Web-interface for editing lang/**.json files and localize models:

This package also provides a simple web interface  at http(s)://yourSiteRoot/localize/. But if you need to change this URI, you can do it through the configuration in config/localize.php:

    'uri' => 'localize',

You can also specify a middleware to access that URL or remove it to open access to all users:

    'access' => ['web','auth'],

All models added to the configuration will be available for translation via the web interface:

    'translated_models' => [
        Post::class
    ],

And you can exclude default locale from editing also in config:

    'default_locale' => 'en',
    'translate_default_locale' => false,

Now it's time for you to create an amazing web application!