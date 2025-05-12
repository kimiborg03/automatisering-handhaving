<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MatchController;
use Illuminate\Support\Facades\Auth;
use App\Models\Matches;

Route::get('/matches', [MatchController::class, 'store'])->name('matches.store');
use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Mail;
use App\Mail\RegisterMail;
use App\Http\Controllers\CategoryController;

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
    $userId = Auth::id(); // Same result
    $allMatches = Matches::all();

    // or: $userId = auth()->user()->id;

    return view('home', compact('userId', 'allMatches'));
})->middleware('auth');


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

// Category routes
Route::get('/category/{category}', [CategoryController::class, 'show'])->name('category.show');
Route::get('/load-matches', [CategoryController::class, 'loadMatches'])->name('matches.load');

// admin page
Route::middleware(['auth', 'admin'])->group(function () {
Route::get('/admin', function () {return view('admin.admin');});
    // routes for register
Route::get('/admin/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/admin/register', [RegisterController::class, 'register']);
});