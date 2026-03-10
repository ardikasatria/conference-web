<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Submission extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'registration_id',
        'user_id',
        'conference_id',
        'title',
        'abstract',
        'keywords',
        'presenter_name',
        'presenter_email',
        'co_authors',
        'topic',
        'subtopics',
        'file_path',
        'status',
        'submission_notes',
        'submitted_at',
    ];

    protected $casts = [
        'keywords' => 'array',
        'co_authors' => 'array',
        'subtopics' => 'array',
        'submitted_at' => 'datetime',
    ];

    /**
     * Get the registration this submission belongs to
     */
    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }

    /**
     * Get the user who submitted
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
     * Get reviews for this submission
     */
    public function reviews()
    {
        return $this->hasMany(SubmissionReview::class);
    }

    /**
     * Get paper reviews for this submission (new reviewer system)
     */
    public function paperReviews()
    {
        return $this->hasMany(PaperReview::class);
    }

    /**
     * Get the latest review
     */
    public function latestReview()
    {
        return $this->reviews()->latest()->first();
    }

    /**
     * Check if submission has been reviewed
     */
    public function isReviewed()
    {
        return $this->reviews()->whereIn('status', ['approved', 'rejected'])->exists();
    }

    /**
     * Check if submission is approved
     */
    public function isApproved()
    {
        return $this->status === 'approved';
    }

    /**
     * Mark as submitted
     */
    public function markAsSubmitted()
    {
        $this->update([
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);

        return $this;
    }

    /**
     * Approve submission (called with review)
     */
    public function approve($notes = null)
    {
        $this->update([
            'status' => 'approved',
            'submission_notes' => $notes,
        ]);

        // Update registration status
        $this->registration->update(['submission_status' => 'approved']);

        return $this;
    }

    /**
     * Reject submission
     */
    public function reject($notes = null)
    {
        $this->update([
            'status' => 'rejected',
            'submission_notes' => $notes,
        ]);

        // Update registration status
        $this->registration->update(['submission_status' => 'rejected']);

        return $this;
    }
}
