<?php

use Illuminate\Support\Facades\Route;
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
