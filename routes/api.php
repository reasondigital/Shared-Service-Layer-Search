<?php

use App\Http\Controllers\Elastic\ArticleController;
use App\Http\Controllers\Elastic\LocationController;
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
                Route::get('/articles', [ArticleController::class, 'get']);
                Route::post('/articles', [ArticleController::class, 'store']);
                Route::put('/articles/{id}', [ArticleController::class, 'update']);
                Route::delete('/articles/{id}', [ArticleController::class, 'destroy']);
                break;
        }
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
                Route::get('/locations', [LocationController::class, 'get']);
                Route::post('/locations', [LocationController::class, 'store']);
                Route::put('/locations/{id}', [LocationController::class, 'update']);
                Route::delete('/locations/{id}', [LocationController::class, 'destroy']);
                break;
        }
    }
);
