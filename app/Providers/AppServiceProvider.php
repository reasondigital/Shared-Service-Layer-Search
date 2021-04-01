<?php

namespace App\Providers;

use App\Http\Response\ApiResponseBuilder;
use App\Http\Response\JsonApiResponseBuilder;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ApiResponseBuilder::class, JsonApiResponseBuilder::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
