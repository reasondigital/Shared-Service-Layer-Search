<?php

use Illuminate\Support\Facades\File;
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
 * Local development
 */
if (app()->environment('local')) {
    /*
     * Note: The development routes file below and the associated controller
     * file at app/Http/DevelopmentController.php are both ignored by the
     * repository. You will need to create them if you haven't already.
     */
    if (File::exists(base_path('routes/api/development.php'))) {
        require base_path('routes/api/development.php');
    }
}

/*
 * Articles
 */
Route::group(
    [
        'prefix' => '/articles',
    ],
    function () {
        switch (config('search.provider.articles')) {
            default:
            case 'elastic':
                $providerControllerClass = App\Http\Controllers\Elastic\ArticleController::class;
                break;
        }

        Route::post('/', [$providerControllerClass, 'store'])->name('articles.post');
        Route::get('/search', [$providerControllerClass, 'search'])->name('articles.search');
        Route::get('/{id}', [$providerControllerClass, 'get'])->name('articles.get');
        Route::put('/{id}', [$providerControllerClass, 'update'])->name('articles.put');
        Route::delete('/{id}', [$providerControllerClass, 'destroy'])->name('articles.delete');
    }
);

/*
 * Locations
 */
Route::group(
    [
        'prefix' => '/locations',
    ],
    function () {
        switch (config('search.provider.locations')) {
            default:
            case 'elastic':
                $providerControllerClass = App\Http\Controllers\Elastic\LocationController::class;
                break;
        }

        Route::post('/', [$providerControllerClass, 'store'])->name('locations.post');
        Route::get('/search', [$providerControllerClass, 'search'])->name('locations.search');
        Route::get('/{id}', [$providerControllerClass, 'get'])->name('locations.get');
        Route::put('/{id}', [$providerControllerClass, 'update'])->name('locations.put');
        Route::delete('/{id}', [$providerControllerClass, 'destroy'])->name('locations.delete');
    }
);

/*
 * Shapes
 */
Route::group(
    [
        'prefix' => '/shapes',
    ],
    function () {
        $controllerClass = App\Http\Controllers\ShapeController::class;

        Route::post('/', [$controllerClass, 'store'])->name('shapes.post');
        Route::get('/list', [$controllerClass, 'list'])->name('shapes.list');
        Route::get('/{shape}', [$controllerClass, 'get'])->name('shapes.get');
        Route::put('/{shape}', [$controllerClass, 'update'])->name('shapes.put');
        Route::delete('/{shape}', [$controllerClass, 'destroy'])->name('shapes.delete');
    }
);
