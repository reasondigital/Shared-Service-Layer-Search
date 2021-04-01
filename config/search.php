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

];
