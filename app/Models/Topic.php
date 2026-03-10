<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Topic extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'slug',
    ];

    /**
     * Get all conferences that have this topic
     */
    public function conferences()
    {
        return $this->belongsToMany(Conference::class, 'conference_topics');
    }
}
