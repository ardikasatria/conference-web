<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Models\Submission;
use App\Models\Conference;
use App\Models\Registration;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubmissionController extends Controller
{
    public function index(Request $request)
    {
        $submissions = Submission::with(['conference', 'registration'])
            ->where('user_id', Auth::id())
            ->when($request->status, fn($q, $s) => $q->where('status', $s))
            ->latest('submitted_at')
            ->paginate(15)
            ->withQueryString();

        return view('participant.submissions.index', compact('submissions'));
    }

    public function create()
    {
        $registrations = Registration::with('conference')
            ->where('user_id', Auth::id())
            ->where('status', 'confirmed')
            ->get();

        $conferences = Conference::where('status', 'published')->orderBy('name')->get(['id', 'name']);
        $topics = Topic::orderBy('name')->get(['id', 'name']);

        return view('participant.submissions.create', compact('registrations', 'conferences', 'topics'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'conference_id' => 'required|exists:conferences,id',
            'registration_id' => 'nullable|exists:registrations,id',
            'title' => 'required|string|max:500',
            'abstract' => 'required|string|min:100',
            'keywords' => 'nullable|string',
            'presenter_name' => 'required|string|max:255',
            'presenter_email' => 'required|email|max:255',
            'co_authors' => 'nullable|string',
            'topic' => 'nullable|string|max:255',
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['status'] = 'draft';
        $validated['submitted_at'] = now();

        if (!empty($validated['keywords'])) {
            $validated['keywords'] = array_map('trim', explode(',', $validated['keywords']));
        }

        if (!empty($validated['co_authors'])) {
            $validated['co_authors'] = array_map('trim', explode(',', $validated['co_authors']));
        }

        if ($request->hasFile('file')) {
            $validated['file_path'] = $request->file('file')->store('submissions', 'public');
        }

        unset($validated['file']);

        Submission::create($validated);

        return redirect()->route('participant.submissions.index')->with('success', 'Submission created successfully.');
    }

    public function edit(Submission $submission)
    {
        if ($submission->user_id !== Auth::id()) {
            abort(403);
        }

        $conferences = Conference::where('status', 'published')->orderBy('name')->get(['id', 'name']);
        $topics = Topic::orderBy('name')->get(['id', 'name']);

        return view('participant.submissions.edit', compact('submission', 'conferences', 'topics'));
    }

    public function update(Request $request, Submission $submission)
    {
        if ($submission->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:500',
            'abstract' => 'required|string|min:100',
            'keywords' => 'nullable|string',
            'presenter_name' => 'required|string|max:255',
            'presenter_email' => 'required|email|max:255',
            'co_authors' => 'nullable|string',
            'topic' => 'nullable|string|max:255',
            'status' => 'nullable|in:draft,submitted',
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ]);

        if (!empty($validated['keywords'])) {
            $validated['keywords'] = array_map('trim', explode(',', $validated['keywords']));
        }

        if (!empty($validated['co_authors'])) {
            $validated['co_authors'] = array_map('trim', explode(',', $validated['co_authors']));
        }

        if ($request->hasFile('file')) {
            $validated['file_path'] = $request->file('file')->store('submissions', 'public');
        }

        unset($validated['file']);

        $submission->update($validated);

        return redirect()->route('participant.submissions.index')->with('success', 'Submission updated successfully.');
    }
}
