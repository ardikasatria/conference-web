<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Session;
use App\Models\Conference;
use App\Models\Speaker;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    public function index(Request $request)
    {
        $sessions = Session::with(['conference', 'speakers'])
            ->when($request->search, fn($q, $s) => $q->where('title', 'like', "%{$s}%"))
            ->when($request->conference_id, fn($q, $c) => $q->where('conference_id', $c))
            ->latest('start_time')
            ->paginate(15)
            ->withQueryString();

        $conferences = Conference::orderBy('name')->get(['id', 'name']);

        return view('admin.sessions.index', compact('sessions', 'conferences'));
    }

    public function create()
    {
        $conferences = Conference::orderBy('name')->get(['id', 'name']);
        $speakers = Speaker::orderBy('name')->get(['id', 'name']);
        return view('admin.sessions.create', compact('conferences', 'speakers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'conference_id' => 'required|exists:conferences,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'room' => 'nullable|string|max:255',
            'capacity' => 'nullable|integer|min:0',
            'status' => 'required|in:scheduled,ongoing,completed,cancelled',
            'speakers' => 'nullable|array',
            'speakers.*' => 'exists:speakers,id',
        ]);

        $session = Session::create(collect($validated)->except('speakers')->toArray());

        if (!empty($validated['speakers'])) {
            $session->speakers()->attach($validated['speakers']);
        }

        return redirect()->route('admin.sessions.index')->with('success', 'Session created successfully.');
    }

    public function edit(Session $session)
    {
        $session->load('speakers');
        $conferences = Conference::orderBy('name')->get(['id', 'name']);
        $speakers = Speaker::orderBy('name')->get(['id', 'name']);
        return view('admin.sessions.edit', compact('session', 'conferences', 'speakers'));
    }

    public function update(Request $request, Session $session)
    {
        $validated = $request->validate([
            'conference_id' => 'required|exists:conferences,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'room' => 'nullable|string|max:255',
            'capacity' => 'nullable|integer|min:0',
            'status' => 'required|in:scheduled,ongoing,completed,cancelled',
            'speakers' => 'nullable|array',
            'speakers.*' => 'exists:speakers,id',
        ]);

        $session->update(collect($validated)->except('speakers')->toArray());
        $session->speakers()->sync($validated['speakers'] ?? []);

        return redirect()->route('admin.sessions.index')->with('success', 'Session updated successfully.');
    }

    public function destroy(Session $session)
    {
        $session->delete();
        return redirect()->route('admin.sessions.index')->with('success', 'Session deleted successfully.');
    }
}
