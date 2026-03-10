<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReviewQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'paper_review_id',
        'question',
        'answer',
        'order',
    ];

    /**
     * Get the paper review
     */
    public function paperReview()
    {
        return $this->belongsTo(PaperReview::class);
    }

    /**
     * Update answer
     */
    public function updateAnswer($answer)
    {
        $this->update(['answer' => $answer]);
        return $this;
    }
}
