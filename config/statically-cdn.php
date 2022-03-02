<?php

return [

    'enabled' => env('CDN_ENABLED', false),

    'cdn-domain' => env('CDN_DOMAIN', 'cdn.statically.io'),

    'site-domain' => env('CDN_SITE_DOMAIN', env('APP_URL')),

    'extensions' => [
        'js' => ['js'],
        'css' => ['css'],
        'img' => ['svg', 'png', 'webp', 'jpg'],
    ],

];
