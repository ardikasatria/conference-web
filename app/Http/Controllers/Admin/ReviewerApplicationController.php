<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReviewerApplication;
use App\Models\Conference;
use Illuminate\Http\Request;

class ReviewerApplicationController extends Controller
{
    public function index(Request $request)
    {
        $applications = ReviewerApplication::with(['user', 'conference', 'topics'])
            ->when($request->search, function ($q, $s) {
                $q->whereHas('user', fn($u) => $u->where('name', 'like', "%{$s}%"));
            })
            ->when($request->conference_id, fn($q, $c) => $q->where('conference_id', $c))
            ->when($request->status, fn($q, $s) => $q->where('status', $s))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $conferences = Conference::orderBy('name')->get(['id', 'name']);

        return view('admin.reviewer-applications.index', compact('applications', 'conferences'));
    }

    public function show(ReviewerApplication $reviewer_application)
    {
        $reviewer_application->load(['user', 'conference', 'topics']);
        return view('admin.reviewer-applications.show', compact('reviewer_application'));
    }

    public function approve(ReviewerApplication $reviewer_application)
    {
        $reviewer_application->approve(auth()->id());

        return redirect()->route('admin.reviewer-applications.index')->with('success', 'Application approved successfully.');
    }

    public function reject(Request $request, ReviewerApplication $reviewer_application)
    {
        $request->validate(['admin_notes' => 'nullable|string']);

        $reviewer_application->reject(auth()->id(), $request->admin_notes);

        return redirect()->route('admin.reviewer-applications.index')->with('success', 'Application rejected.');
    }

    public function destroy(ReviewerApplication $reviewer_application)
    {
        $reviewer_application->delete();
        return redirect()->route('admin.reviewer-applications.index')->with('success', 'Application deleted successfully.');
    }
}
