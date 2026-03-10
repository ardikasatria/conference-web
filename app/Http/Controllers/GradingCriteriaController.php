<?php

namespace App\Http\Controllers;

use App\Models\GradingCriteria;
use App\Models\Conference;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class GradingCriteriaController extends Controller
{
    use AuthorizesRequests;

    /**
     * Get all grading criteria
     */
    public function index(Request $request)
    {
        $query = GradingCriteria::with('conference');

        // Filter by conference
        if ($request->has('conference_id')) {
            $query->where('conference_id', $request->input('conference_id'));
        }

        // Search
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }

        $criteria = $query->orderBy('order')
            ->paginate($request->input('per_page', 20));

        return response()->json($criteria);
    }

    /**
     * Create grading criteria (admin)
     */
    public function store(Request $request, Conference $conferenceId)
    {
        $this->authorize('isAdmin', auth()->user());

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'max_score' => 'required|numeric|min:1|max:100',
            'order' => 'nullable|integer|min:0',
        ]);

        // Get next order number if not provided
        if (!isset($validated['order'])) {
            $validated['order'] = $conferenceId->gradingCriteria()
                ->max('order') + 1 ?? 1;
        }

        $criteria = $conferenceId->gradingCriteria()->create($validated);

        return response()->json([
            'message' => 'Grading criteria created',
            'criteria' => $criteria,
        ], 201);
    }

    /**
     * Get specific grading criteria
     */
    public function show(GradingCriteria $id)
    {
        return response()->json(
            $id->load(['conference', 'reviewGrades' => function($q) {
                $q->select('id', 'grading_criteria_id', 'score', 'notes', 'created_at');
            }])
        );
    }

    /**
     * Update grading criteria (admin)
     */
    public function update(Request $request, GradingCriteria $id)
    {
        $this->authorize('isAdmin', auth()->user());

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'max_score' => 'required|numeric|min:1|max:100',
            'order' => 'nullable|integer|min:0',
        ]);

        $id->update($validated);

        return response()->json([
            'message' => 'Grading criteria updated',
            'criteria' => $id,
        ]);
    }

    /**
     * Delete grading criteria (admin)
     */
    public function destroy(GradingCriteria $id)
    {
        $this->authorize('isAdmin', auth()->user());

        // Check if criteria has any grades
        if ($id->reviewGrades()->count() > 0) {
            return response()->json([
                'message' => 'Cannot delete criteria that has grades assigned',
                'grades_count' => $id->reviewGrades()->count(),
            ], 409);
        }

        $id->delete();

        return response()->json(['message' => 'Grading criteria deleted']);
    }

    /**
     * Get grading criteria for specific conference
     */
    public function conferenceCriteria(Conference $conferenceId, Request $request)
    {
        $criteria = $conferenceId->gradingCriteria()
            ->orderBy('order')
            ->get();

        // Calculate statistics if requested
        $statistics = null;
        if ($request->has('include_stats')) {
            $statistics = [];
            foreach ($criteria as $c) {
                $grades = $c->reviewGrades;
                $statistics[$c->id] = [
                    'name' => $c->name,
                    'total_grades' => $grades->count(),
                    'average_score' => $grades->count() > 0 ? round($grades->avg('score'), 2) : 0,
                    'highest_score' => $grades->count() > 0 ? $grades->max('score') : 0,
                    'lowest_score' => $grades->count() > 0 ? $grades->min('score') : 0,
                ];
            }
        }

        return response()->json([
            'conference_id' => $conferenceId->id,
            'conference_name' => $conferenceId->name,
            'total_max_score' => $criteria->sum('max_score'),
            'count' => $criteria->count(),
            'criteria' => $criteria,
            'statistics' => $statistics,
        ]);
    }
}
