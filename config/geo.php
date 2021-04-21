<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Geo Coding
    |--------------------------------------------------------------------------
    |
    | Configure relevant values for the geocoding facility in the application.
    |
    */

    'coding' => [
        'provider' => env('GEOCODING_PROVIDER', 'openstreetmap'),
        'api' => [
            'url' => env('GEOCODING_API_URL'),
            'key' => env('GEOCODING_API_KEY'),
        ],
    ],

];
