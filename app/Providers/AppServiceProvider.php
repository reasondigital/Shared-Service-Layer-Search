<?php

namespace App\Providers;

use App\Http\Response\ApiResponseBuilder;
use App\Http\Response\JsonApiResponseBuilder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
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
        $this->app->bind(LengthAwarePaginator::class, \App\Pagination\LengthAwarePaginator::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->addArrMacros();
    }

    /**
     * @since 1.0.0
     */
    private function addArrMacros()
    {
        /**
         * Looks for the keys given in `$keysToWrap`, wraps them in an array
         * keyed with `$wrapperKey` and returns the resulting array.
         *
         * If the key already exists, it will be overwritten.
         *
         * @param array  $array
         * @param string $wrapperKey
         * @param array  $keysToWrap
         *
         * @return array
         * @since 1.0.0
         */
        Arr::macro('wrapKeysWithin', function (array $array, string $wrapperKey, array $keysToWrap) {
            $wrapper = [];

            foreach ($keysToWrap as $key) {
                if (!array_key_exists($key, $array)) {
                    continue;
                }

                $wrapper[$key] = $array[$key];
                unset($array[$key]);
            }

            $array[$wrapperKey] = $wrapper;
            return $array;
        });
    }
}
