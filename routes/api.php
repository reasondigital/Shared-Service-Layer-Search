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
                Route::get('/article', [ArticleController::class, 'get']);
                Route::post('/article', [ArticleController::class, 'store']);
                Route::put('/article/{article}', [ArticleController::class, 'update']);
                Route::delete('/article/{article}', [ArticleController::class, 'destroy']);
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
                Route::get('/location', [LocationController::class, 'get']);
                Route::post('/location', [LocationController::class, 'store']);
                Route::put('/location/{id}', [LocationController::class, 'update']);
                Route::delete('/location/{id}', [LocationController::class, 'destroy']);
                break;
        }
    }
);
