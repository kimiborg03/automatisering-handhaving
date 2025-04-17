<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MatchController;

Route::post('/matches', [MatchController::class, 'store'])->name('matches.store');

// GET route for login page
Route::get('/login', function () {
    return view('login');
})->name('login');

// Route::post('/login', [AuthController::class, 'login']);

// POST route for /login
Route::post('/login', [AuthController::class, 'login']);

// POST route for /logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Route for home, only available for logged in users
Route::get('/home', function () {
    return view('home');
})->middleware('auth');

Route::get('/add-match', [MatchController::class, 'show'])->name('add-match');
