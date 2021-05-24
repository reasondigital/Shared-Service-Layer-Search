<?php

return [

    /*
    |--------------------------------------------------------------------------
    | API Tokens
    |--------------------------------------------------------------------------
    |
    | Manually create "anonymous" access tokens. Generate your own token string
    | and add it to the 'tokens' config. You'll then need to provide that
    | token with its 'abilities' so that it has established permissions.
    |
    */
    /**
     * @see App\Constants\ApiAbilities For individual API abilities.
     * @see App\Constants\AccessLevels For roles comprised of API abilities.
     *
     * @link https://codepen.io/corenominal/full/rxOmMJ Generate keys online.
     */

    'tokens' => [
        /*
         * Examples:
         *
        'xxxxxxxx-xxxx-xxxx-xxxx-keyexample01' => [
            'abilities' => App\Constants\AccessLevels::READ_PUBLIC,
        ],
        'xxxxxxxx-xxxx-xxxx-xxxx-keyexample02' => [
            'abilities' => App\Constants\AccessLevels::WRITE,
        ],
        'xxxxxxxx-xxxx-xxxx-xxxx-keyexample03' => [
            'abilities' => [
                App\Constants\ApiAbilities::READ_PUBLIC,
                App\Constants\ApiAbilities::READ_SENSITIVE,
                App\Constants\ApiAbilities::ADMINISTRATE,
            ],
        ],
        */
    ],

];
