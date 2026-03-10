<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaperReview;
use App\Models\Submission;
use App\Models\User;
use App\Models\Conference;
use Illuminate\Http\Request;

class PaperReviewController extends Controller
{
    public function index(Request $request)
    {
        $reviews = PaperReview::with(['submission', 'reviewer', 'conference'])
            ->when($request->search, function ($q, $s) {
                $q->whereHas('submission', fn($sub) => $sub->where('title', 'like', "%{$s}%"))
                  ->orWhereHas('reviewer', fn($r) => $r->where('name', 'like', "%{$s}%"));
            })
            ->when($request->conference_id, fn($q, $c) => $q->where('conference_id', $c))
            ->when($request->status, fn($q, $s) => $q->where('status', $s))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $conferences = Conference::orderBy('name')->get(['id', 'name']);

        return view('admin.paper-reviews.index', compact('reviews', 'conferences'));
    }

    public function create()
    {
        $submissions = Submission::whereIn('status', ['submitted', 'under_review'])
            ->orderBy('title')
            ->get(['id', 'title']);
        $reviewers = User::whereHas('roles', fn($q) => $q->where('name', 'reviewer'))
            ->orderBy('name')
            ->get(['id', 'name', 'email']);
        $conferences = Conference::orderBy('name')->get(['id', 'name']);

        return view('admin.paper-reviews.create', compact('submissions', 'reviewers', 'conferences'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'submission_id' => 'required|exists:submissions,id',
            'reviewer_id' => 'required|exists:users,id',
            'conference_id' => 'required|exists:conferences,id',
        ]);

        $validated['status'] = 'pending';

        PaperReview::create($validated);

        return redirect()->route('admin.paper-reviews.index')->with('success', 'Paper review assignment created successfully.');
    }

    public function show(PaperReview $paper_review)
    {
        $paper_review->load(['submission', 'reviewer', 'conference', 'grades.criteria', 'questions']);
        return view('admin.paper-reviews.show', compact('paper_review'));
    }

    public function destroy(PaperReview $paper_review)
    {
        $paper_review->delete();
        return redirect()->route('admin.paper-reviews.index')->with('success', 'Paper review deleted successfully.');
    }
}
