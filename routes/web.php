<?php

use Illuminate\Support\Facades\Route;
<<<<<<< HEAD
use App\Http\Controllers\RoutingController;
use App\Http\Controllers\DashboardController;

// Public Inertia.js routes
Route::group(['prefix' => '/'], function () {
    Route::get('', [RoutingController::class, 'index'])->name('root');
    Route::get('committee', [RoutingController::class, 'committee'])->name('committee');
    Route::get('schedule', [RoutingController::class, 'schedule'])->name('schedule');
    Route::get('information', [RoutingController::class, 'information'])->name('information');
    Route::get('program', [RoutingController::class, 'program'])->name('program');
    Route::get('author', [RoutingController::class, 'author'])->name('author');
    Route::get('{first}/{second}/{third}', [RoutingController::class, 'thirdLevel'])->name('third');
    Route::get('{first}/{second}', [RoutingController::class, 'secondLevel'])->name('second');
    Route::get('{any}', [RoutingController::class, 'root'])->name('any');
});

// Dashboard routes - protected by auth middleware
Route::middleware('auth')->group(function () {
    // General dashboard - routes to appropriate dashboard based on user role
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Admin dashboard
    Route::get('/dashboard/admin', [DashboardController::class, 'showAdminDashboard'])
        ->middleware('role:admin')
        ->name('dashboard.admin');
    
    // Participant dashboard
    Route::get('/dashboard/participant', [DashboardController::class, 'showParticipantDashboard'])
        ->middleware('role:participant')
        ->name('dashboard.participant');
    
    // Reviewer dashboard
    Route::get('/dashboard/reviewer', [DashboardController::class, 'showReviewerDashboard'])
        ->middleware('role:reviewer')
        ->name('dashboard.reviewer');
});
=======
use App\Http\Controllers\PageController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

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
});

/*
|--------------------------------------------------------------------------
| Public Inertia Pages
|--------------------------------------------------------------------------
*/
Route::get('/', [PageController::class, 'home'])->name('home');

>>>>>>> 5df23c4b72a88fa1680b7f582ebf9dba246fddaf
