<?php

namespace App\Http\Controllers\Reviewer;

use App\Http\Controllers\Controller;
use App\Models\PaperReview;
use App\Models\GradingCriteria;
use App\Models\Conference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssignedPaperController extends Controller
{
    public function index(Request $request)
    {
        $reviews = PaperReview::with(['submission.user', 'conference', 'grades'])
            ->where('reviewer_id', Auth::id())
            ->when($request->status, fn($q, $s) => $q->where('status', $s))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('reviewer.assigned-papers.index', compact('reviews'));
    }

    public function show(PaperReview $paper_review)
    {
        if ($paper_review->reviewer_id !== Auth::id()) {
            abort(403);
        }

        $paper_review->load(['submission.user', 'conference', 'grades.criteria', 'questions']);

        $conference = $paper_review->conference;
        $gradingCriteria = $conference
            ? GradingCriteria::where('conference_id', $conference->id)->orderBy('order')->get()
            : collect();

        return view('reviewer.assigned-papers.show', compact('paper_review', 'gradingCriteria'));
    }

    public function startReview(PaperReview $paper_review)
    {
        if ($paper_review->reviewer_id !== Auth::id()) {
            abort(403);
        }

        $paper_review->update(['status' => 'in_progress']);

        return redirect()->route('reviewer.assigned-papers.show', $paper_review)->with('success', 'Review started.');
    }

    public function submitReview(Request $request, PaperReview $paper_review)
    {
        if ($paper_review->reviewer_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'comments' => 'required|string',
            'recommendation' => 'required|in:accept,minor_revision,major_revision,reject',
            'grades' => 'required|array',
            'grades.*.criteria_id' => 'required|exists:grading_criteria,id',
            'grades.*.score' => 'required|numeric|min:0',
            'grades.*.notes' => 'nullable|string',
        ]);

        foreach ($validated['grades'] as $grade) {
            $paper_review->addGrade($grade['criteria_id'], $grade['score'], $grade['notes'] ?? null);
        }

        $paper_review->submitReview($validated['recommendation'], $validated['comments']);

        return redirect()->route('reviewer.assigned-papers.index')->with('success', 'Review submitted successfully.');
    }
}
