<?php

$google_api = env('API_GOOGLE_MAPS', '');

return [

    'admin.css' => [
        'set'    => 'styles',
        'path'   => 'vendor/clumsy/cms/css/admin.css',
        'req'    => 'bootstrap',
        'v'      => '0.28.0',
        'elixir' => false,
    ],

    'admin.js' => [
        'set'    => 'footer',
        'path'   => 'vendor/clumsy/cms/js/admin.js',
        'req'    => ['bootstrap.js', 'chosen'],
        'v'      => '0.28.0',
        'elixir' => false,
    ],

    'google-maps-admin' => [
        'set'   => 'footer',
        'path'  => "https://maps.googleapis.com/maps/api/js?key={$google_api}",
    ],
];
