<?php

use Illuminate\Support\Facades\Route;
use Keasy9\Localize\Facades\Localize;
use Keasy9\Localize\Http\Controllers\FileStringController;
use Keasy9\Localize\Http\Controllers\LocaleController;
use Keasy9\Localize\Http\Controllers\TranslationController;

Route::group([
    'prefix' => Localize::getLocalePrefix().'/'.config('localize.uri', 'localize'),
    'middleware' => config('localize.access', []),
], function () {

    Route::name('localize.')->group(function () {

        Route::get('', [LocaleController::class, 'index'])->name('locales');

        Route::group(['prefix' => '{locale}'], function () {

            Route::group([
                'prefix' => 'file',
                'controller' => FileStringController::class,
            ], function () {

                Route::name('file.')->group(function () {

                    Route::get('autofill', 'autofill')->name('autofill');
                    Route::get('export', 'export')->name('export');
                    Route::post('import', 'import')->name('import');

                    Route::group(['prefix' => 'strings'], function () {

                        Route::name('strings.')->group(function () {

                            Route::get('', 'index')->name('index');
                            Route::post('save', 'save')->name('save');
                            Route::get('{string}/delete', 'destroy')->name('destroy');

                        });

                    });

                });

            });

            Route::group([
                'prefix' => 'models/{model}',
                'controller' => TranslationController::class,
            ], function () {

                Route::name('models.')->group(function () {

                    Route::get('', 'index')->name('index');
                    Route::post('{id}/save', 'save')->name('save');

                });

            });

        });

    });

});
