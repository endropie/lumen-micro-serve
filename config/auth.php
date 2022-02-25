<?php

return [
        /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    |
    | This option controls the default authentication "guard" and password
    | reset options for your application. You may change these defaults
    | as required, but they're a perfect start for most applications.
    |
    */

    'defaults' => [
        'guard' => 'jwt',
    ],

    'guards' => [
    	'jwt' => [
            'driver' => 'jwt',
            'provider' => 'users',
        ],
    	'token' => [
            'driver' => 'jwt',
            'provider' => 'token',
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => \App\Models\User::class,
        ],
    ],
];
