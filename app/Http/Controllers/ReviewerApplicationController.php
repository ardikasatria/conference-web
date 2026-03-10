<?php

namespace App\Http\Controllers;

use App\Models\ReviewerApplication;
use App\Models\Conference;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ReviewerApplicationController extends Controller
{
    use AuthorizesRequests;

    /**
     * Store a new reviewer application
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'conference_id' => 'required|exists:conferences,id',
            'motivation' => 'required|string|min:50|max:1000',
            'expertise' => 'required|string|min:50|max:1000',
            'field_of_study' => 'required|string|max:255',
            'sub_field' => 'required|string|max:255',
            'selected_topics' => 'required|array|min:1',
            'selected_topics.*' => 'exists:topics,id',
            'full_name_with_degree' => 'required|string|max:255',
            'affiliation' => 'required|string|max:255',
        ]);

        // Check if user already has a pending or approved application
        $existing = ReviewerApplication::where('user_id', auth()->id())
            ->where('conference_id', $validated['conference_id'])
            ->whereIn('status', ['pending', 'approved'])
            ->first();

        if ($existing) {
            return response()->json([
                'message' => 'You already have a pending or approved application for this conference',
                'status' => $existing->status,
            ], 409);
        }

        // Create application
        $application = ReviewerApplication::create([
            'user_id' => auth()->id(),
            ...$validated,
            'status' => 'pending',
        ]);

        // Sync topics
        $application->syncTopics($validated['selected_topics']);

        return response()->json([
            'message' => 'Application submitted successfully',
            'application' => $application->load('topics', 'user'),
        ], 201);
    }

    /**
     * Get specific reviewer application
     */
    public function show(ReviewerApplication $id)
    {
        // Check authorization
        if ($id->user_id !== auth()->id() && !auth()->user()?->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json(
            $id->load(['user', 'conference', 'topics', 'reviewer'])
        );
    }

    /**
     * Update reviewer application (only if pending)
     */
    public function update(Request $request, ReviewerApplication $id)
    {
        // Authorization
        if ($id->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Only allow updates if pending
        if ($id->status !== 'pending') {
            return response()->json([
                'message' => 'Can only update pending applications',
            ], 422);
        }

        $validated = $request->validate([
            'motivation' => 'sometimes|required|string|min:50|max:1000',
            'expertise' => 'sometimes|required|string|min:50|max:1000',
            'field_of_study' => 'sometimes|required|string|max:255',
            'sub_field' => 'sometimes|required|string|max:255',
            'selected_topics' => 'sometimes|required|array|min:1',
            'selected_topics.*' => 'exists:topics,id',
            'full_name_with_degree' => 'sometimes|required|string|max:255',
            'affiliation' => 'sometimes|required|string|max:255',
        ]);

        $id->update($validated);

        if (isset($validated['selected_topics'])) {
            $id->syncTopics($validated['selected_topics']);
        }

        return response()->json([
            'message' => 'Application updated',
            'application' => $id->fresh(['topics', 'user']),
        ]);
    }

    /**
     * Delete reviewer application (only if pending)
     */
    public function destroy(ReviewerApplication $id)
    {
        // Authorization
        if ($id->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Only allow deletion if pending
        if ($id->status !== 'pending') {
            return response()->json([
                'message' => 'Can only delete pending applications',
            ], 422);
        }

        $id->delete();

        return response()->json(['message' => 'Application deleted']);
    }

    /**
     * Get applications for specific conference (admin)
     */
    public function conferenceApplications(Conference $conferenceId, Request $request)
    {
        $this->authorize('isAdmin', auth()->user());

        $query = $conferenceId->reviewerApplications()
            ->with(['user', 'topics', 'reviewer']);

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }

        // Pagination
        $applications = $query->paginate($request->input('per_page', 15));

        return response()->json($applications);
    }

    /**
     * Get pending applications for conference
     */
    public function pendingApplications(Conference $conferenceId)
    {
        $this->authorize('isAdmin', auth()->user());

        $applications = $conferenceId->pendingReviewerApplications()
            ->with(['user', 'topics'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'count' => $applications->count(),
            'applications' => $applications,
        ]);
    }

    /**
     * Approve reviewer application (admin)
     */
    public function approve(ReviewerApplication $id, Request $request)
    {
        $this->authorize('isAdmin', auth()->user());

        if ($id->status !== 'pending') {
            return response()->json([
                'message' => 'Only pending applications can be approved',
            ], 422);
        }

        $validated = $request->validate([
            'notes' => 'nullable|string|max:500',
        ]);

        $id->approve(auth()->id(), $validated['notes'] ?? null);

        return response()->json([
            'message' => 'Application approved',
            'application' => $id->fresh(['user', 'conference', 'reviewer']),
        ]);
    }

    /**
     * Reject reviewer application (admin)
     */
    public function reject(ReviewerApplication $id, Request $request)
    {
        $this->authorize('isAdmin', auth()->user());

        if ($id->status !== 'pending') {
            return response()->json([
                'message' => 'Only pending applications can be rejected',
            ], 422);
        }

        $validated = $request->validate([
            'notes' => 'required|string|min:10|max:500',
        ]);

        $id->reject(auth()->id(), $validated['notes']);

        return response()->json([
            'message' => 'Application rejected',
            'application' => $id->fresh(['user', 'reviewer']),
        ]);
    }

    /**
     * Get my applications (authenticated user)
     */
    public function myApplications()
    {
        $applications = auth()->user()
            ->reviewerApplications()
            ->with(['conference', 'topics', 'reviewer'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'count' => $applications->count(),
            'applications' => $applications,
        ]);
    }

    /**
     * Get my application detail (authenticated user)
     */
    public function myApplicationDetail(ReviewerApplication $id)
    {
        if ($id->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json(
            $id->load(['conference', 'topics', 'reviewer'])
        );
    }
}
