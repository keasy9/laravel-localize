<?php

use Illuminate\Support\Facades\Route;
use Keasy9\Localize\Http\Controllers\LocalizationController;
use Keasy9\Localize\Facades\Localize;

Route::group([
    'prefix' => Localize::getLocalePrefix() . '/' . config('localize.uri', 'localize'),
    'controller' => LocalizationController::class,
    'middleware' => config('localize.access', []),
], function() {
    Route::redirect('/', 'localize/files');

    Route::name('localize.')->group(function() {
        Route::get('files', 'files')->name('files');
        Route::get('files/{file}', 'file')->name('file');
        Route::get('files/{file}/autofill', 'fillFile')->name('file.autofill');
        Route::get('files/{file}/export', 'exportFile')->name('file.export');
        Route::post('files/{file}/import', 'importFile')->name('file.import');
    
        Route::get('files/{file}/strings/{key}/delete', 'deleteString')->name('file.deleteString');
        Route::post('files/{file}/strings/save', 'saveString')->name('file.saveString');
    });
});
