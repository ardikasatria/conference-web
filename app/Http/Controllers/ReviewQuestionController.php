<?php

namespace App\Http\Controllers;

use App\Models\ReviewQuestion;
use App\Models\PaperReview;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ReviewQuestionController extends Controller
{
    use AuthorizesRequests;

    /**
     * Get review question
     */
    public function show(ReviewQuestion $id)
    {
        // Check authorization
        $review = $id->paperReview;

        if ($review->reviewer_id !== auth()->id() && !auth()->user()?->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($id);
    }

    /**
     * Update answer for review question
     */
    public function updateAnswer(Request $request, ReviewQuestion $id)
    {
        // Check authorization
        $review = $id->paperReview;

        if ($review->reviewer_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'answer' => 'required|string|max:2000',
        ]);

        $id->updateAnswer($validated['answer']);

        return response()->json([
            'message' => 'Answer updated',
            'question' => $id->fresh(),
        ]);
    }

    /**
     * Delete answer from review question
     */
    public function deleteAnswer(ReviewQuestion $id)
    {
        // Check authorization
        $review = $id->paperReview;

        if ($review->reviewer_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $id->update(['answer' => null]);

        return response()->json(['message' => 'Answer deleted']);
    }
}
