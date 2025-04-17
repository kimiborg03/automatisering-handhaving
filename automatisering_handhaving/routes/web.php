<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/wedstrijd-toevoegen', function () {
    return view('welcome');
});
