<?php

namespace App\Http\Controllers;

use App\Models\ReviewGrade;
use App\Models\PaperReview;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ReviewGradeController extends Controller
{
    use AuthorizesRequests;

    /**
     * Create review grade (reviewer)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'paper_review_id' => 'required|exists:paper_reviews,id',
            'grading_criteria_id' => 'required|exists:grading_criteria,id',
            'score' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:500',
        ]);

        // Get review and check ownership
        $review = PaperReview::find($validated['paper_review_id']);

        if ($review->reviewer_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Validate score doesn't exceed max_score
        $criteria = $review->conference->gradingCriteria()
            ->where('id', $validated['grading_criteria_id'])
            ->first();

        if (!$criteria) {
            return response()->json(['message' => 'Criteria not found'], 404);
        }

        if ($validated['score'] > $criteria->max_score) {
            return response()->json([
                'message' => "Score cannot exceed {$criteria->max_score}",
            ], 422);
        }

        $grade = ReviewGrade::create($validated);

        return response()->json([
            'message' => 'Grade created',
            'grade' => $grade->load('criteria'),
        ], 201);
    }

    /**
     * Update review grade
     */
    public function update(Request $request, ReviewGrade $id)
    {
        // Check authorization through paper review
        $review = $id->paperReview;

        if ($review->reviewer_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'score' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:500',
        ]);

        // Validate score
        $criteria = $review->conference->gradingCriteria()
            ->where('id', $id->grading_criteria_id)
            ->first();

        if ($validated['score'] > $criteria->max_score) {
            return response()->json([
                'message' => "Score cannot exceed {$criteria->max_score}",
            ], 422);
        }

        $id->update($validated);

        return response()->json([
            'message' => 'Grade updated',
            'grade' => $id->fresh('criteria'),
        ]);
    }

    /**
     * Delete review grade
     */
    public function destroy(ReviewGrade $id)
    {
        // Check authorization
        $review = $id->paperReview;

        if ($review->reviewer_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $id->delete();

        return response()->json(['message' => 'Grade deleted']);
    }
}
