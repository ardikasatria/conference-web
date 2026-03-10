<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubmissionReview extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'submission_id',
        'reviewed_by',
        'conference_id',
        'status',
        'comments',
        'rating',
        'revision_notes',
        'requires_revision',
        'reviewed_at',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
        'requires_revision' => 'boolean',
    ];

    /**
     * Get the submission being reviewed
     */
    public function submission()
    {
        return $this->belongsTo(Submission::class);
    }

    /**
     * Get the admin who reviewed
     */
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Get the conference
     */
    public function conference()
    {
        return $this->belongsTo(Conference::class);
    }

    /**
     * Approve submission
     */
    public function approve($comments = null)
    {
        $this->update([
            'status' => 'approved',
            'comments' => $comments,
            'reviewed_at' => now(),
        ]);

        // Update submission status
        $this->submission->approve($comments);

        return $this;
    }

    /**
     * Reject submission
     */
    public function reject($comments = null)
    {
        $this->update([
            'status' => 'rejected',
            'comments' => $comments,
            'reviewed_at' => now(),
        ]);

        // Update submission status
        $this->submission->reject($comments);

        return $this;
    }

    /**
     * Request revision
     */
    public function requestRevision($revisionNotes)
    {
        $this->update([
            'status' => 'revision_requested',
            'revision_notes' => $revisionNotes,
            'requires_revision' => true,
            'reviewed_at' => now(),
        ]);

        return $this;
    }
}
