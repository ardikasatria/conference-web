<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradingCriteria extends Model
{
    use HasFactory;

    protected $fillable = [
        'conference_id',
        'name',
        'description',
        'max_score',
        'order',
    ];

    /**
     * Get the conference
     */
    public function conference()
    {
        return $this->belongsTo(Conference::class);
    }

    /**
     * Get all review grades for this criteria
     */
    public function reviewGrades()
    {
        return $this->hasMany(ReviewGrade::class);
    }
}
