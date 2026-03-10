<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReviewerApplicationController;
use App\Http\Controllers\PaperReviewController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\GradingCriteriaController;
use App\Http\Controllers\ReviewGradeController;
use App\Http\Controllers\ReviewQuestionController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

/**
 * ==========================================
 * REVIEWER APPLICATIONS ENDPOINTS
 * ==========================================
 */
Route::prefix('reviewer-applications')->group(function () {
    // User applies to become reviewer
    Route::post('/', [ReviewerApplicationController::class, 'store']);
    Route::get('/{id}', [ReviewerApplicationController::class, 'show']);
    Route::put('/{id}', [ReviewerApplicationController::class, 'update']);
    Route::delete('/{id}', [ReviewerApplicationController::class, 'destroy']);

    // Admin only
    Route::middleware('admin')->group(function () {
        Route::post('/{id}/approve', [ReviewerApplicationController::class, 'approve']);
        Route::post('/{id}/reject', [ReviewerApplicationController::class, 'reject']);
    });
});

/**
 * ==========================================
 * PAPER REVIEWS ENDPOINTS
 * ==========================================
 */
Route::prefix('paper-reviews')->group(function () {
    // Create review assignment (admin only)
    Route::post('/', [PaperReviewController::class, 'store'])->middleware('admin');

    Route::get('/{id}', [PaperReviewController::class, 'show']);
    Route::put('/{id}', [PaperReviewController::class, 'update']);
    Route::delete('/{id}', [PaperReviewController::class, 'destroy']);

    // Reviewer operations
    Route::post('/{id}/start-review', [PaperReviewController::class, 'startReview']);
    Route::post('/{id}/add-grade', [PaperReviewController::class, 'addGrade']);
    Route::post('/{id}/submit-review', [PaperReviewController::class, 'submitReview']);

    // Track progress
    Route::get('/{id}/progress', [PaperReviewController::class, 'progress']);
});

/**
 * ==========================================
 * SUBMISSION REVIEWS ENDPOINTS
 * ==========================================
 */
Route::get('submissions/{id}/reviews', [PaperReviewController::class, 'submissionReviews']);

/**
 * ==========================================
 * CONFERENCE-SPECIFIC ENDPOINTS
 * ==========================================
 */
Route::prefix('conferences/{conferenceId}')->group(function () {
    // Reviewer applications
    Route::get('reviewer-applications', [ReviewerApplicationController::class, 'conferenceApplications']);
    Route::get('reviewer-applications/pending', [ReviewerApplicationController::class, 'pendingApplications']);

    // Papers reviews
    Route::get('paper-reviews', [PaperReviewController::class, 'conferenceReviews'])->middleware('admin');

    // Topics
    Route::get('topics', [TopicController::class, 'conferenceTopics']);
    Route::post('topics', [TopicController::class, 'attachTopic'])->middleware('admin');
    Route::delete('topics/{topicId}', [TopicController::class, 'detachTopic'])->middleware('admin');

    // Grading criteria
    Route::get('grading-criteria', [GradingCriteriaController::class, 'conferenceCriteria']);
    Route::post('grading-criteria', [GradingCriteriaController::class, 'store'])->middleware('admin');
    Route::put('grading-criteria/{id}', [GradingCriteriaController::class, 'update'])->middleware('admin');
    Route::delete('grading-criteria/{id}', [GradingCriteriaController::class, 'destroy'])->middleware('admin');
});

/**
 * ==========================================
 * TOPICS ENDPOINTS
 * ==========================================
 */
Route::prefix('topics')->group(function () {
    Route::get('/', [TopicController::class, 'index']);
    Route::post('/', [TopicController::class, 'store'])->middleware('admin');
    Route::get('/{id}', [TopicController::class, 'show']);
    Route::put('/{id}', [TopicController::class, 'update'])->middleware('admin');
    Route::delete('/{id}', [TopicController::class, 'destroy'])->middleware('admin');
});

/**
 * ==========================================
 * GRADING CRITERIA ENDPOINTS
 * ==========================================
 */
Route::prefix('grading-criteria')->group(function () {
    Route::get('/', [GradingCriteriaController::class, 'index']);
    Route::get('/{id}', [GradingCriteriaController::class, 'show']);
});

/**
 * ==========================================
 * REVIEW GRADES ENDPOINTS
 * ==========================================
 */
Route::prefix('review-grades')->group(function () {
    Route::post('/', [ReviewGradeController::class, 'store']);
    Route::put('/{id}', [ReviewGradeController::class, 'update']);
    Route::delete('/{id}', [ReviewGradeController::class, 'destroy']);
});

/**
 * ==========================================
 * REVIEW QUESTIONS ENDPOINTS
 * ==========================================
 */
Route::prefix('review-questions')->group(function () {
    Route::get('/{id}', [ReviewQuestionController::class, 'show']);
    Route::put('/{id}/update-answer', [ReviewQuestionController::class, 'updateAnswer']);
    Route::post('/{id}/delete-answer', [ReviewQuestionController::class, 'deleteAnswer']);
});

/**
 * ==========================================
 * REVIEWER DASHBOARD ENDPOINTS
 * ==========================================
 */
Route::prefix('reviewer')->middleware('auth:sanctum')->group(function () {
    // My assigned reviews
    Route::get('reviews', [PaperReviewController::class, 'myReviews']);
    Route::get('reviews/{id}', [PaperReviewController::class, 'myReviewDetail']);

    // My applications
    Route::get('applications', [ReviewerApplicationController::class, 'myApplications']);
    Route::get('applications/{id}', [ReviewerApplicationController::class, 'myApplicationDetail']);

    // Statistics
    Route::get('statistics', [PaperReviewController::class, 'myStatistics']);
});
