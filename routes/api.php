<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*
 * Articles
 */
Route::group(
    [
        'prefix' => '/search/' . config('app.api_version'),
    ],
    function () {
        switch (config('search.provider.articles')) {
            default:
            case 'elastic':
                $providerControllerClass = App\Http\Controllers\Elastic\ArticleController::class;
                break;
        }

        Route::post('/articles', [$providerControllerClass, 'store'])->name('articles.post');
        Route::get('/articles/{id}', [$providerControllerClass, 'get'])->name('articles.get');
        Route::get('/articles', [$providerControllerClass, 'search'])->name('articles.search');
        Route::put('/articles/{id}', [$providerControllerClass, 'update'])->name('articles.put');
        Route::delete('/articles/{id}', [$providerControllerClass, 'destroy'])->name('articles.delete');
    }
);

/*
 * Locations
 */
Route::group(
    [
        'prefix' => '/search/' . config('app.api_version'),
    ],
    function () {
        switch (config('search.provider.locations')) {
            default:
            case 'elastic':
                $providerControllerClass = App\Http\Controllers\Elastic\LocationController::class;
                break;
        }

        Route::post('/locations', [$providerControllerClass, 'store'])->name('locations.post');
        Route::get('/locations/{id}', [$providerControllerClass, 'get'])->name('locations.get');
        Route::get('/locations', [$providerControllerClass, 'search'])->name('locations.search');
        Route::put('/locations/{id}', [$providerControllerClass, 'update'])->name('locations.put');
        Route::delete('/locations/{id}', [$providerControllerClass, 'destroy'])->name('locations.delete');
    }
);
