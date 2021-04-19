<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Active Search Provider
    |--------------------------------------------------------------------------
    |
    | For now, all resources must utilise the same search integration driver.
    | This configuration has been added in consideration of a potential future
    | feature, where different providers can service different resources.
    |
    */

    'provider' => [
        'articles' => env('SEARCH_PROVIDER_ARTICLES', env('SCOUT_DRIVER', 'elastic')),
        'locations' => env('SEARCH_PROVIDER_LOCATIONS', env('SCOUT_DRIVER', 'elastic')),
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Results Count
    |--------------------------------------------------------------------------
    |
    | The default number of items returned in a set of search results.
    | Endpoints may accommodate options that allow this value to be changed at
    | the point of querying.
    |
    */

    'results_per_page' => [
        'articles' => (int) env('RESULTS_COUNT_ARTICLES', 10),
        'locations' => (int) env('RESULTS_COUNT_LOCATIONS', 10),
    ],

];
