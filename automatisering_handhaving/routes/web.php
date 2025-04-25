<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MatchController;
use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Mail;
use App\Mail\RegisterMail;

// GET route for login page
Route::get('/login', function () {
    return view('login');
})->name('login');

// POST route for /login
Route::post('/login', [AuthController::class, 'login']);

// POST route for /logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Route for home, only available for logged in users
Route::get('/home', function () {
    return view('home');
})->middleware('auth');

// routes for register
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::get('/test-email', function () {
    $user = (object) [
        'name' => 'Test User',
        'email' => 'test@example.com',
    ];

    Mail::to('158205@student.talland.nl')->send(new RegisterMail($user));

    return 'Test email sent!';
});

// Match routes
Route::post('/matches', [MatchController::class, 'store'])->name('matches.store');
Route::get('/add-match', [MatchController::class, 'show'])->name('add-match');
