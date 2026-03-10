<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ProfileController;

// Admin Controllers
use App\Http\Controllers\Admin\ConferenceController as AdminConferenceController;
use App\Http\Controllers\Admin\TopicController as AdminTopicController;
use App\Http\Controllers\Admin\SessionController as AdminSessionController;
use App\Http\Controllers\Admin\PackageController as AdminPackageController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\RoleController as AdminRoleController;
use App\Http\Controllers\Admin\RegistrationController as AdminRegistrationController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\SubmissionController as AdminSubmissionController;
use App\Http\Controllers\Admin\GradingCriteriaController as AdminGradingCriteriaController;
use App\Http\Controllers\Admin\ReviewerApplicationController as AdminReviewerApplicationController;
use App\Http\Controllers\Admin\PaperReviewController as AdminPaperReviewController;
use App\Http\Controllers\Admin\SpeakerController as AdminSpeakerController;

// Participant Controllers
use App\Http\Controllers\Participant\SubmissionController as ParticipantSubmissionController;
use App\Http\Controllers\Participant\PaymentController as ParticipantPaymentController;
use App\Http\Controllers\Participant\SessionController as ParticipantSessionController;

// Reviewer Controllers
use App\Http\Controllers\Reviewer\AssignedPaperController;

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

    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

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
    | Admin Resource Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:admin')->prefix('dashboard/admin')->name('admin.')->group(function () {
        // Conference Management
        Route::resource('conferences', AdminConferenceController::class);
        Route::resource('topics', AdminTopicController::class);
        Route::resource('sessions', AdminSessionController::class);
        Route::resource('packages', AdminPackageController::class);
        Route::resource('speakers', AdminSpeakerController::class);

        // User Management
        Route::resource('users', AdminUserController::class);
        Route::resource('roles', AdminRoleController::class);

        // Registrations & Payments
        Route::resource('registrations', AdminRegistrationController::class);
        Route::resource('payments', AdminPaymentController::class)->only(['index', 'show', 'update', 'destroy']);

        // Submissions & Grading
        Route::resource('submissions', AdminSubmissionController::class)->only(['index', 'show', 'update', 'destroy']);
        Route::resource('grading-criteria', AdminGradingCriteriaController::class);

        // Reviewer Management
        Route::resource('reviewer-applications', AdminReviewerApplicationController::class)->only(['index', 'show', 'destroy']);
        Route::post('reviewer-applications/{reviewer_application}/approve', [AdminReviewerApplicationController::class, 'approve'])->name('reviewer-applications.approve');
        Route::post('reviewer-applications/{reviewer_application}/reject', [AdminReviewerApplicationController::class, 'reject'])->name('reviewer-applications.reject');

        Route::resource('paper-reviews', AdminPaperReviewController::class)->only(['index', 'create', 'store', 'show', 'destroy']);
    });

    /*
    |--------------------------------------------------------------------------
    | Reviewer Resource Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:reviewer')->prefix('dashboard/reviewer')->name('reviewer.')->group(function () {
        Route::get('assigned-papers', [AssignedPaperController::class, 'index'])->name('assigned-papers.index');
        Route::get('assigned-papers/{paper_review}', [AssignedPaperController::class, 'show'])->name('assigned-papers.show');
        Route::post('assigned-papers/{paper_review}/start-review', [AssignedPaperController::class, 'startReview'])->name('assigned-papers.start-review');
        Route::post('assigned-papers/{paper_review}/submit-review', [AssignedPaperController::class, 'submitReview'])->name('assigned-papers.submit-review');
    });

    /*
    |--------------------------------------------------------------------------
    | Participant Resource Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('dashboard/participant')->name('participant.')->group(function () {
        Route::resource('submissions', ParticipantSubmissionController::class)->only(['index', 'create', 'store', 'edit', 'update']);
        Route::resource('payments', ParticipantPaymentController::class)->only(['index', 'show']);
        Route::post('payments/{payment}/upload-proof', [ParticipantPaymentController::class, 'uploadProof'])->name('payments.upload-proof');
        Route::get('sessions', [ParticipantSessionController::class, 'index'])->name('sessions.index');
    });
});

/*
|--------------------------------------------------------------------------
| Public Inertia Pages
|--------------------------------------------------------------------------
*/
Route::get('/', [PageController::class, 'home'])->name('home');

