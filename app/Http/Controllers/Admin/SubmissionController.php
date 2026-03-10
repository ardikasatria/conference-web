<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Submission;
use App\Models\Conference;
use Illuminate\Http\Request;

class SubmissionController extends Controller
{
    public function index(Request $request)
    {
        $submissions = Submission::with(['user', 'conference', 'registration'])
            ->when($request->search, fn($q, $s) => $q->where('title', 'like', "%{$s}%")->orWhere('presenter_name', 'like', "%{$s}%"))
            ->when($request->conference_id, fn($q, $c) => $q->where('conference_id', $c))
            ->when($request->status, fn($q, $s) => $q->where('status', $s))
            ->latest('submitted_at')
            ->paginate(15)
            ->withQueryString();

        $conferences = Conference::orderBy('name')->get(['id', 'name']);

        return view('admin.submissions.index', compact('submissions', 'conferences'));
    }

    public function show(Submission $submission)
    {
        $submission->load(['user', 'conference', 'registration', 'reviews', 'paperReviews.reviewer']);
        return view('admin.submissions.show', compact('submission'));
    }

    public function update(Request $request, Submission $submission)
    {
        $validated = $request->validate([
            'status' => 'required|in:draft,submitted,under_review,revision_required,accepted,rejected',
        ]);

        $submission->update($validated);

        return redirect()->route('admin.submissions.index')->with('success', 'Submission status updated successfully.');
    }

    public function destroy(Submission $submission)
    {
        $submission->delete();
        return redirect()->route('admin.submissions.index')->with('success', 'Submission deleted successfully.');
    }
}
