<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    if (app()->environment('production')) {
        return '';
    } else {
        $apiSchema = json_decode(File::get(base_path('docs/openapi.json')), true);
        return response()->json($apiSchema);
    }
});
