<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MatchController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\AccountController;
use App\Mail\RegisterMail;
use App\Http\Controllers\PasswordSetupController;
// Login routes
Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// routes for logged in users
Route::middleware('auth')->group(function () {

    // Home route (authenticated)
    Route::get('/home', [HomeController::class, 'index'])->middleware('auth')->name('home');

    // Match routes
    Route::post('/matches', [MatchController::class, 'store'])->name('matches.store');
    Route::get('/matches', [MatchController::class, 'store']); // Optional: waarom GET voor store?
    Route::get('/admin/add-match', [MatchController::class, 'show'])->name('admin.add-match');
    Route::post('/match/{matchId}/user/remove', [MatchController::class, 'deleteUserFromMatch']);
    Route::post('/match/{matchId}/update', action: [MatchController::class, 'updateMatch']);

    // Category routes
    Route::get('/category/{category}', [CategoryController::class, 'show'])->name('category.show');
    Route::get('/load-matches', [CategoryController::class, 'loadMatches'])->name('matches.load');

    // deadline routes
    Route::post('/admin/match/{id}/set-deadline-now', [MatchController::class, 'setDeadlineToNow'])->middleware('auth');
    Route::post('/admin/match/{id}/remove-deadline', [MatchController::class, 'removeDeadline'])->middleware('auth');

    // Route for the account page
    Route::get('/account', function () {return view('account');})->name('account');

    // password setup route
    Route::get('/password/setup/{token}', [RegisterController::class, 'showPasswordSetupForm'])->name('password.setup.form')->middleware('signed');
    // save password route
    Route::post('/password/setup', [PasswordSetupController::class, 'setPassword'])->name('password.setup.submit');
});

// Admin-only routes
Route::middleware(['auth', 'admin'])->group(function () {

    //  route for admin dashboard
    Route::get('/admin', function () {return view('admin.admin');});
    
    //  route for admin classes page
    Route::get('/admin/classes', function () {$groups = DB::table('groups')->get();return view('admin.classes', compact('groups'));})->name('admin.classes');
    //  route for adding a class
    Route::post('/admin/classes/addclass', [GroupController::class, 'addclass'])->name('admin.classes.addclass');
    //  route for deleting a class
    Route::delete('/admin/classes/{id}', [GroupController::class, 'deleteclass'])->name('admin.classes.deleteclass');
    //  route for editing a class
    Route::put('/admin/classes/{id}', [GroupController::class, 'updateclass'])->name('admin.classes.updateclass');

    //  route for user manage page
    Route::get('/admin/users', function () {return view('admin.users');})->name('admin.users');
    Route::get('/admin/users', [UsersController::class, 'index'])->name('admin.users');
    Route::put('/admin/users/{id}', [UsersController::class, 'update'])->name('admin.users.update');

    // route for the register page
    Route::get('/admin/register', [RegisterController::class, 'showRegistrationForm'])->name('admin.register');
    Route::post('/admin/register', [RegisterController::class, 'register']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    // page for match registrations
    Route::get('/matches/{match}/registrations', [MatchController::class, 'showRegistrations'])->name('matches.registrations');
    Route::post('/matches/{match}/presence', [MatchController::class, 'updatePresence']);

    // route for downloading excel file of match registrations   
    Route::get('/matches/{match}/export-excel', [MatchController::class, 'exportExcel'])->name('matches.exportExcel');
    // route for deleting a match
    Route::post('/admin/match/{id}/delete', [MatchController::class, 'deleteMatch'])->middleware('auth');
    // route for removing a deadline
    Route::post('/admin/match/{id}/remove-deadline', [MatchController::class, 'removeDeadline'])->middleware('auth');
    // route for updating a match
    Route::put('/admin/match/{match}/update', [MatchController::class, 'update'])->name('admin.matches.update');
});