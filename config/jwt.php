<?php

return [
    'secret' => env('JWT_SECRET', env('APP_KEY')),
    'alg' => env('JWT_ALG'),
//    'expired' => 2400, // in seconds
    'ttl' => env('JWT_TTL', 40 * 60),
    'issuer'      => env('JWT_ISSUER', config('app.url'))
];