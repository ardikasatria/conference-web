<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use App\Models\Conference;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TopicController extends Controller
{
    use AuthorizesRequests;

    /**
     * Get all topics
     */
    public function index(Request $request)
    {
        $query = Topic::query();

        // Search
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }

        $topics = $query->orderBy('name')
            ->paginate($request->input('per_page', 20));

        return response()->json($topics);
    }

    /**
     * Create topic (admin)
     */
    public function store(Request $request)
    {
        $this->authorize('isAdmin', auth()->user());

        $validated = $request->validate([
            'name' => 'required|string|unique:topics|max:255',
            'description' => 'nullable|string|max:1000',
            'slug' => 'nullable|string|unique:topics|max:255',
        ]);

        // Generate slug if not provided
        if (!isset($validated['slug'])) {
            $validated['slug'] = \Illuminate\Support\Str::slug($validated['name']);
        }

        $topic = Topic::create($validated);

        return response()->json([
            'message' => 'Topic created',
            'topic' => $topic,
        ], 201);
    }

    /**
     * Get specific topic
     */
    public function show(Topic $id)
    {
        return response()->json(
            $id->load(['conferences' => function($q) {
                $q->orderBy('start_date', 'desc');
            }])
        );
    }

    /**
     * Update topic (admin)
     */
    public function update(Request $request, Topic $id)
    {
        $this->authorize('isAdmin', auth()->user());

        $validated = $request->validate([
            'name' => "required|string|unique:topics,name,{$id->id}|max:255",
            'description' => 'nullable|string|max:1000',
            'slug' => "nullable|string|unique:topics,slug,{$id->id}|max:255",
        ]);

        // Update slug if name changed
        if (!isset($validated['slug']) && $validated['name'] !== $id->name) {
            $validated['slug'] = \Illuminate\Support\Str::slug($validated['name']);
        }

        $id->update($validated);

        return response()->json([
            'message' => 'Topic updated',
            'topic' => $id,
        ]);
    }

    /**
     * Delete topic (admin)
     */
    public function destroy(Topic $id)
    {
        $this->authorize('isAdmin', auth()->user());

        // Check if topic is used in any conference
        if ($id->conferences()->count() > 0) {
            return response()->json([
                'message' => 'Cannot delete topic that is assigned to conferences',
                'conferences_count' => $id->conferences()->count(),
            ], 409);
        }

        $id->delete();

        return response()->json(['message' => 'Topic deleted']);
    }

    /**
     * Get topics for specific conference
     */
    public function conferenceTopics(Conference $conferenceId, Request $request)
    {
        $topics = $conferenceId->topics()
            ->orderBy('order')
            ->get();

        return response()->json([
            'conference_id' => $conferenceId->id,
            'conference_name' => $conferenceId->name,
            'count' => $topics->count(),
            'topics' => $topics,
        ]);
    }

    /**
     * Attach topic to conference (admin)
     */
    public function attachTopic(Request $request, Conference $conferenceId)
    {
        $this->authorize('isAdmin', auth()->user());

        $validated = $request->validate([
            'topic_id' => 'required|exists:topics,id',
            'order' => 'nullable|integer|min:0',
        ]);

        // Check if already attached
        if ($conferenceId->topics()->where('topic_id', $validated['topic_id'])->exists()) {
            return response()->json([
                'message' => 'Topic already attached to this conference',
            ], 409);
        }

        $order = $validated['order'] ?? $conferenceId->topics()->count() + 1;

        $conferenceId->topics()->attach(
            $validated['topic_id'],
            ['order' => $order]
        );

        return response()->json([
            'message' => 'Topic attached to conference',
            'topic' => Topic::find($validated['topic_id']),
        ], 201);
    }

    /**
     * Detach topic from conference (admin)
     */
    public function detachTopic(Request $request, Conference $conferenceId, Topic $topicId)
    {
        $this->authorize('isAdmin', auth()->user());

        // Check if attached
        if (!$conferenceId->topics()->where('topic_id', $topicId->id)->exists()) {
            return response()->json([
                'message' => 'Topic not attached to this conference',
            ], 404);
        }

        $conferenceId->topics()->detach($topicId->id);

        return response()->json(['message' => 'Topic detached from conference']);
    }
}
