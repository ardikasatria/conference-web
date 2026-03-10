<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReviewerApplication extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'conference_id',
        'motivation',
        'expertise',
        'status',
        'admin_notes',
        'reviewed_by',
        'reviewed_at',
        'field_of_study',
        'sub_field',
        'selected_topics',
        'full_name_with_degree',
        'affiliation',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
        'selected_topics' => 'array',
    ];

    /**
     * Get the user who applied
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the conference
     */
    public function conference()
    {
        return $this->belongsTo(Conference::class);
    }

    /**
     * Get the admin who reviewed this application
     */
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Get selected topics
     */
    public function topics()
    {
        return $this->belongsToMany(Topic::class, 'reviewer_application_topics');
    }

    /**
     * Approve reviewer application
     */
    public function approve($adminId, $notes = null)
    {
        $this->update([
            'status' => 'approved',
            'reviewed_by' => $adminId,
            'admin_notes' => $notes,
            'reviewed_at' => now(),
        ]);

        // Assign reviewer role to user
        $reviewerRole = Role::where('name', 'reviewer')->first();
        $this->user->assignRole($reviewerRole, $this->conference);

        return $this;
    }

    /**
     * Reject reviewer application
     */
    public function reject($adminId, $notes = null)
    {
        $this->update([
            'status' => 'rejected',
            'reviewed_by' => $adminId,
            'admin_notes' => $notes,
            'reviewed_at' => now(),
        ]);

        return $this;
    }

    /**
     * Sync selected topics
     */
    public function syncTopics($topicIds = [])
    {
        $this->topics()->sync($topicIds);
        $this->update(['selected_topics' => $topicIds]);
        return $this;
    }

    /**
     * Get selected topic names
     */
    public function getSelectedTopicNames()
    {
        return $this->topics()->pluck('name')->toArray();
    }
}
