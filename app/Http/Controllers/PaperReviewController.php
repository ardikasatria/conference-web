<?php

namespace App\Http\Controllers;

use App\Models\PaperReview;
use App\Models\Submission;
use App\Models\Conference;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PaperReviewController extends Controller
{
    use AuthorizesRequests;

    /**
     * Create review assignment (admin only)
     */
    public function store(Request $request)
    {
        $this->authorize('isAdmin', auth()->user());

        $validated = $request->validate([
            'submission_id' => 'required|exists:submissions,id',
            'reviewer_id' => 'required|exists:users,id',
            'conference_id' => 'required|exists:conferences,id',
        ]);

        // Check if review already exists
        $existing = PaperReview::where('submission_id', $validated['submission_id'])
            ->where('reviewer_id', $validated['reviewer_id'])
            ->first();

        if ($existing) {
            return response()->json([
                'message' => 'Review assignment already exists',
                'review_id' => $existing->id,
            ], 409);
        }

        $review = PaperReview::create([
            ...$validated,
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Review assigned',
            'review' => $review->load(['submission', 'reviewer', 'conference']),
        ], 201);
    }

    /**
     * Get specific review
     */
    public function show(PaperReview $id)
    {
        // Authorization: reviewer or admin can view
        if ($id->reviewer_id !== auth()->id() && !auth()->user()?->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json(
            $id->load(['submission.registration.user', 'reviewer', 'conference', 'grades.criteria', 'questions'])
        );
    }

    /**
     * Update review
     */
    public function update(Request $request, PaperReview $id)
    {
        // Only reviewer or admin can update
        if ($id->reviewer_id !== auth()->id() && !auth()->user()?->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'comments' => 'nullable|string|max:5000',
        ]);

        $id->update($validated);

        return response()->json([
            'message' => 'Review updated',
            'review' => $id->fresh(['grades.criteria', 'questions']),
        ]);
    }

    /**
     * Delete review
     */
    public function destroy(PaperReview $id)
    {
        $this->authorize('isAdmin', auth()->user());

        $id->delete();

        return response()->json(['message' => 'Review deleted']);
    }

    /**
     * Start reviewing
     */
    public function startReview(PaperReview $id)
    {
        // Only assigned reviewer can start
        if ($id->reviewer_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($id->status !== 'pending') {
            return response()->json([
                'message' => 'Review already started',
            ], 422);
        }

        $id->update(['status' => 'in_progress']);

        return response()->json([
            'message' => 'Review started',
            'status' => $id->status,
        ]);
    }

    /**
     * Add or update grade for criteria
     */
    public function addGrade(PaperReview $id, Request $request)
    {
        // Only assigned reviewer can grade
        if ($id->reviewer_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'grading_criteria_id' => 'required|exists:grading_criteria,id',
            'score' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:500',
        ]);

        // Validate score doesn't exceed max_score
        $criteria = $id->conference->gradingCriteria()
            ->where('id', $validated['grading_criteria_id'])
            ->first();

        if (!$criteria) {
            return response()->json(['message' => 'Criteria not found in this conference'], 404);
        }

        if ($validated['score'] > $criteria->max_score) {
            return response()->json([
                'message' => "Score cannot exceed {$criteria->max_score}",
            ], 422);
        }

        // Create or update grade
        $grade = $id->grades()
            ->updateOrCreate(
                ['grading_criteria_id' => $validated['grading_criteria_id']],
                [
                    'score' => $validated['score'],
                    'notes' => $validated['notes'] ?? null,
                ]
            );

        return response()->json([
            'message' => 'Grade saved',
            'grade' => $grade->load('criteria'),
        ]);
    }

    /**
     * Submit review final
     */
    public function submitReview(PaperReview $id, Request $request)
    {
        // Only assigned reviewer can submit
        if ($id->reviewer_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'recommendation' => 'required|string|min:20|max:2000',
            'recommend_accept' => 'nullable|boolean',
        ]);

        // Check if all criteria are graded
        $conference = $id->conference;
        $criteriaCount = $conference->gradingCriteria()->count();
        $gradesCount = $id->grades()->count();

        if ($gradesCount < $criteriaCount) {
            return response()->json([
                'message' => "All {$criteriaCount} criteria must be graded",
                'graded' => $gradesCount,
                'total' => $criteriaCount,
            ], 422);
        }

        $id->submitReview(
            $validated['recommendation'],
            $validated['recommend_accept']
        );

        return response()->json([
            'message' => 'Review submitted',
            'review' => $id->fresh(['grades.criteria', 'questions']),
        ]);
    }

    /**
     * Get review progress
     */
    public function progress(PaperReview $id)
    {
        // Authorization
        if ($id->reviewer_id !== auth()->id() && !auth()->user()?->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $conference = $id->conference;
        $criteria = $conference->gradingCriteria()->get();
        $grades = $id->grades()->with('criteria')->get();

        $progress = [];
        foreach ($criteria as $c) {
            $grade = $grades->firstWhere('grading_criteria_id', $c->id);
            $progress[] = [
                'criteria_id' => $c->id,
                'criteria_name' => $c->name,
                'max_score' => $c->max_score,
                'scored' => $grade ? true : false,
                'score' => $grade?->score,
            ];
        }

        return response()->json([
            'status' => $id->status,
            'total_score' => $id->total_score,
            'progress_percentage' => $id->getProgressPercentage(),
            'criteria' => $progress,
        ]);
    }

    /**
     * Get all reviews for a submission
     */
    public function submissionReviews(Submission $id)
    {
        $this->authorize('isAdmin', auth()->user());

        $reviews = $id->reviews()
            ->with(['reviewer', 'grades.criteria', 'questions'])
            ->orderBy('created_at', 'desc')
            ->get();

        $averageScore = $reviews->avg('total_score');
        $acceptCount = $reviews->where('recommend_accept', true)->count();
        $rejectCount = $reviews->where('recommend_accept', false)->count();
        $pendingCount = $reviews->where('status', '!=', 'completed')->count();

        return response()->json([
            'submission' => $id->load('registration.user'),
            'reviews' => $reviews,
            'statistics' => [
                'total_reviews' => $reviews->count(),
                'average_score' => round($averageScore, 2),
                'accept_count' => $acceptCount,
                'reject_count' => $rejectCount,
                'pending_count' => $pendingCount,
            ],
        ]);
    }

    /**
     * Get all reviews for conference (admin)
     */
    public function conferenceReviews(Conference $conferenceId, Request $request)
    {
        $this->authorize('isAdmin', auth()->user());

        $query = $conferenceId->paperReviews()
            ->with(['submission.registration.user', 'reviewer']);

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }

        // Filter by recommendation
        if ($request->has('recommend_accept')) {
            $value = $request->input('recommend_accept');
            if ($value === 'true' || $value === 'true') {
                $query->where('recommend_accept', true);
            } elseif ($value === 'false') {
                $query->where('recommend_accept', false);
            }
        }

        $reviews = $query->orderBy('created_at', 'desc')
            ->paginate($request->input('per_page', 20));

        return response()->json($reviews);
    }

    /**
     * Get my assigned reviews (authenticated reviewer)
     */
    public function myReviews(Request $request)
    {
        $query = auth()->user()->paperReviewsAsReviewer();

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }

        // Filter by conference
        if ($request->has('conference_id')) {
            $query->where('conference_id', $request->input('conference_id'));
        }

        $reviews = $query->with(['submission.registration.user', 'conference'])
            ->orderBy('created_at', 'desc')
            ->paginate($request->input('per_page', 10));

        return response()->json($reviews);
    }

    /**
     * Get my review detail
     */
    public function myReviewDetail(PaperReview $id)
    {
        if ($id->reviewer_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json(
            $id->load([
                'submission.registration.user',
                'conference',
                'grades.criteria',
                'questions',
            ])
        );
    }

    /**
     * Get my statistics
     */
    public function myStatistics()
    {
        $reviews = auth()->user()->paperReviewsAsReviewer()->get();

        $completed = $reviews->where('status', 'completed')->count();
        $inProgress = $reviews->where('status', 'in_progress')->count();
        $pending = $reviews->where('status', 'pending')->count();
        $avgScore = $reviews->avg('total_score');
        $acceptCount = $reviews->where('recommend_accept', true)->count();
        $rejectCount = $reviews->where('recommend_accept', false)->count();

        return response()->json([
            'total_assigned' => $reviews->count(),
            'completed' => $completed,
            'in_progress' => $inProgress,
            'pending' => $pending,
            'average_score' => $avgScore ? round($avgScore, 2) : 0,
            'recommendations' => [
                'accept' => $acceptCount,
                'reject' => $rejectCount,
                'pending' => $pending,
            ],
        ]);
    }
}
