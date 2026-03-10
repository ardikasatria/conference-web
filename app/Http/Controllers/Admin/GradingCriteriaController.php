<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GradingCriteria;
use App\Models\Conference;
use Illuminate\Http\Request;

class GradingCriteriaController extends Controller
{
    public function index(Request $request)
    {
        $criteria = GradingCriteria::with('conference')
            ->when($request->search, fn($q, $s) => $q->where('name', 'like', "%{$s}%"))
            ->when($request->conference_id, fn($q, $c) => $q->where('conference_id', $c))
            ->orderBy('order')
            ->paginate(15)
            ->withQueryString();

        $conferences = Conference::orderBy('name')->get(['id', 'name']);

        return view('admin.grading-criteria.index', compact('criteria', 'conferences'));
    }

    public function create()
    {
        $conferences = Conference::orderBy('name')->get(['id', 'name']);
        return view('admin.grading-criteria.create', compact('conferences'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'conference_id' => 'required|exists:conferences,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'max_score' => 'required|integer|min:1|max:100',
            'order' => 'nullable|integer|min:0',
        ]);

        GradingCriteria::create($validated);

        return redirect()->route('admin.grading-criteria.index')->with('success', 'Grading criteria created successfully.');
    }

    public function edit(GradingCriteria $grading_criterion)
    {
        $conferences = Conference::orderBy('name')->get(['id', 'name']);
        return view('admin.grading-criteria.edit', compact('grading_criterion', 'conferences'));
    }

    public function update(Request $request, GradingCriteria $grading_criterion)
    {
        $validated = $request->validate([
            'conference_id' => 'required|exists:conferences,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'max_score' => 'required|integer|min:1|max:100',
            'order' => 'nullable|integer|min:0',
        ]);

        $grading_criterion->update($validated);

        return redirect()->route('admin.grading-criteria.index')->with('success', 'Grading criteria updated successfully.');
    }

    public function destroy(GradingCriteria $grading_criterion)
    {
        $grading_criterion->delete();
        return redirect()->route('admin.grading-criteria.index')->with('success', 'Grading criteria deleted successfully.');
    }
}
