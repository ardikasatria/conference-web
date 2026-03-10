<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

/*
|--------------------------------------------------------------------------
| Dashboard Routes (auth-protected)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/dashboard/admin', [DashboardController::class, 'admin'])
        ->middleware('role:admin')
        ->name('dashboard.admin');

    Route::get('/dashboard/participant', [DashboardController::class, 'participant'])
        ->name('dashboard.participant');

    Route::get('/dashboard/reviewer', [DashboardController::class, 'reviewer'])
        ->middleware('role:reviewer')
        ->name('dashboard.reviewer');

    /*
    |--------------------------------------------------------------------------
    | Admin Resource Routes (placeholder pages - CRUD managed via API)
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:admin')->prefix('dashboard/admin')->name('admin.')->group(function () {
        // Conferences
        Route::get('/conferences', fn() => view('dashboard.admin'))->name('conferences.index');
        Route::get('/topics', fn() => view('dashboard.admin'))->name('topics.index');
        Route::get('/sessions', fn() => view('dashboard.admin'))->name('sessions.index');
        Route::get('/packages', fn() => view('dashboard.admin'))->name('packages.index');

        // Users
        Route::get('/users', fn() => view('dashboard.admin'))->name('users.index');
        Route::get('/roles', fn() => view('dashboard.admin'))->name('roles.index');

        // Registrations
        Route::get('/registrations', fn() => view('dashboard.admin'))->name('registrations.index');
        Route::get('/payments', fn() => view('dashboard.admin'))->name('payments.index');

        // Submissions
        Route::get('/submissions', fn() => view('dashboard.admin'))->name('submissions.index');
        Route::get('/grading-criteria', fn() => view('dashboard.admin'))->name('grading-criteria.index');

        // Reviewers
        Route::get('/reviewer-applications', fn() => view('dashboard.admin'))->name('reviewer-applications.index');
        Route::get('/paper-reviews', fn() => view('dashboard.admin'))->name('paper-reviews.index');

        // Speakers
        Route::get('/speakers', fn() => view('dashboard.admin'))->name('speakers.index');
    });

    /*
    |--------------------------------------------------------------------------
    | Reviewer Resource Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:reviewer')->prefix('dashboard/reviewer')->name('reviewer.')->group(function () {
        Route::get('/assigned-papers', fn() => view('dashboard.reviewer'))->name('assigned-papers');
    });

    /*
    |--------------------------------------------------------------------------
    | Participant Resource Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('dashboard/participant')->name('participant.')->group(function () {
        Route::get('/submissions', fn() => view('dashboard.participant'))->name('submissions.index');
        Route::get('/payments', fn() => view('dashboard.participant'))->name('payments.index');
        Route::get('/sessions', fn() => view('dashboard.participant'))->name('sessions.index');
    });
});

/*
|--------------------------------------------------------------------------
| Public Inertia Pages
|--------------------------------------------------------------------------
*/
Route::get('/', [PageController::class, 'home'])->name('home');

