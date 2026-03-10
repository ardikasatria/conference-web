<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReviewGrade extends Model
{
    use HasFactory;

    protected $fillable = [
        'paper_review_id',
        'grading_criteria_id',
        'score',
        'notes',
    ];

    protected $casts = [
        'score' => 'decimal:2',
    ];

    /**
     * Get the paper review
     */
    public function paperReview()
    {
        return $this->belongsTo(PaperReview::class);
    }

    /**
     * Get the grading criteria
     */
    public function criteria()
    {
        return $this->belongsTo(GradingCriteria::class, 'grading_criteria_id');
    }
}
