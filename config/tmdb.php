<?php
return [
    'api_key' => env('TMDB_API_KEY'),
    'api_token' => env('TMDB_API_TOKEN'),
    'base_url' => env('TMDB_BASE_URL', 'https://api.themoviedb.org/3'),
    'image_base_url' => 'https://image.tmdb.org/t/p/',
    'image_sizes' => [
        'poster' => 'w500',
        'backdrop' => 'w1280',
        'profile' => 'w185',
    ],
];
