<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test-api-route', function () {
    return 'Funciona desde web.php';
});
