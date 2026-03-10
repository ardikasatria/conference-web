<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaperReview extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'submission_id',
        'reviewer_id',
        'conference_id',
        'review_date',
        'total_score',
        'status',
        'comments',
        'recommendation',
        'recommend_accept',
    ];

    protected $casts = [
        'review_date' => 'datetime',
        'total_score' => 'decimal:2',
        'recommend_accept' => 'boolean',
    ];

    /**
     * Get the submission being reviewed
     */
    public function submission()
    {
        return $this->belongsTo(Submission::class);
    }

    /**
     * Get the reviewer
     */
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    /**
     * Get the conference
     */
    public function conference()
    {
        return $this->belongsTo(Conference::class);
    }

    /**
     * Get all grades for this review
     */
    public function grades()
    {
        return $this->hasMany(ReviewGrade::class);
    }

    /**
     * Get all review questions
     */
    public function questions()
    {
        return $this->hasMany(ReviewQuestion::class)->orderBy('order');
    }

    /**
     * Add grade untuk criteria
     */
    public function addGrade($criteriaId, $score, $notes = null)
    {
        return ReviewGrade::create([
            'paper_review_id' => $this->id,
            'grading_criteria_id' => $criteriaId,
            'score' => $score,
            'notes' => $notes,
        ]);
    }

    /**
     * Calculate total score dari semua grades
     */
    public function calculateTotalScore()
    {
        $totalScore = $this->grades()->sum('score');
        $this->update(['total_score' => $totalScore]);
        return $totalScore;
    }

    /**
     * Add review question & answer
     */
    public function addQuestion($question, $answer = null, $order = 0)
    {
        return ReviewQuestion::create([
            'paper_review_id' => $this->id,
            'question' => $question,
            'answer' => $answer,
            'order' => $order,
        ]);
    }

    /**
     * Mark review as completed
     */
    public function markAsCompleted()
    {
        $this->calculateTotalScore();
        $this->update(['status' => 'completed', 'review_date' => now()]);
        return $this;
    }

    /**
     * Submit review result
     */
    public function submitReview($recommendation, $recommendAccept = null)
    {
        $this->update([
            'status' => $recommendAccept ? 'accepted' : ($recommendAccept === false ? 'rejected' : 'completed'),
            'recommendation' => $recommendation,
            'recommend_accept' => $recommendAccept,
            'review_date' => now(),
        ]);

        return $this;
    }

    /**
     * Check if review is complete (all grades filled)
     */
    public function isComplete()
    {
        $conference = $this->conference;
        $criteriaCount = $conference->gradingCriteria()->count();
        $gradesCount = $this->grades()->count();

        return $criteriaCount > 0 && $gradesCount === $criteriaCount && $this->total_score !== null;
    }

    /**
     * Get review progress percentage
     */
    public function getProgressPercentage()
    {
        $conference = $this->conference;
        $criteriaCount = $conference->gradingCriteria()->count();
        
        if ($criteriaCount === 0) {
            return 0;
        }

        $gradesCount = $this->grades()->count();
        return ($gradesCount / $criteriaCount) * 100;
    }
}
