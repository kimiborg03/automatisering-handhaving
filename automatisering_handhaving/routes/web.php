<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MatchController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\CategoryController;
use App\Mail\RegisterMail;

// Login routes
Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Register routes (public)
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Home route (authenticated)
Route::get('/home', [HomeController::class, 'index'])->middleware('auth')->name('home');

// Match routes
Route::post('/matches', [MatchController::class, 'store'])->name('matches.store');
Route::get('/matches', [MatchController::class, 'store']); // Optional: waarom GET voor store?
Route::get('/admin/add-match', [MatchController::class, 'show'])->name('admin.add-match');
Route::post('/match/{matchId}/user/remove', [MatchController::class, 'deleteUserFromMatch']);
Route::post('/match/{matchId}/update', [MatchController::class, 'updateMatch']);

// Category routes
Route::get('/category/{category}', [CategoryController::class, 'show'])->name('category.show');
Route::get('/load-matches', [CategoryController::class, 'loadMatches'])->name('matches.load');

// Test email route
Route::get('/test-email', function () {
    $user = (object) [
        'name' => 'Test User',
        'email' => 'test@example.com',
    ];

    Mail::to('158205@student.talland.nl')->send(new RegisterMail($user));
    return 'Test email sent!';
});

// Admin-only routes
Route::middleware(['auth', 'admin'])->group(function () {
    //  route for admin dashboard
    Route::get('/admin', function () {return view('admin.admin');});
    
    //  route for admin classes page
Route::get('/admin/classes', function () {return view('admin.classes');})->name('admin.classes');

    Route::get('/admin/register', [RegisterController::class, 'showRegistrationForm'])->name('admin.register');
    Route::post('/admin/register', [RegisterController::class, 'register']);
});

