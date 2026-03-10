<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Speaker extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'bio',
        'image',
        'company',
        'position',
        'website',
        'twitter',
        'linkedin',
        'status',
    ];

    /**
     * Get all sessions for this speaker
     */
    public function sessions()
    {
        return $this->belongsToMany(Session::class, 'session_speaker');
    }

    /**
     * Get speaker sessions with pivot data
     */
    public function sessionDetails()
    {
        return $this->belongsToMany(Session::class, 'session_speaker')
                    ->withPivot('is_moderator', 'order')
                    ->withTimestamps();
    }
}
