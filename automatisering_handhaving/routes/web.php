<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MatchController;
use Illuminate\Support\Facades\Auth;
use App\Models\Matches;

Route::get('/matches', [MatchController::class, 'store'])->name('matches.store');
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

// // Route for home, only available for logged in users
// Route::get('/home', function () {

// })->middleware('auth');

// routes for register
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::get('/home', [HomeController::class, 'index'])->middleware('auth');;

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
Route::post('/match/{matchId}/user/remove', [MatchController::class, 'deleteUserFromMatch']);
Route::post('/match/{matchId}/update', [MatchController::class, 'updateMatch']);
