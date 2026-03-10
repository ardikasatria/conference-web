<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Session extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'conference_sessions';

    protected $fillable = [
        'conference_id',
        'title',
        'description',
        'start_time',
        'end_time',
        'room',
        'capacity',
        'status',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    /**
     * Get the conference that this session belongs to
     */
    public function conference()
    {
        return $this->belongsTo(Conference::class);
    }

    /**
     * Get all speakers for this session
     */
    public function speakers()
    {
        return $this->belongsToMany(Speaker::class, 'session_speaker')
                    ->withPivot('is_moderator', 'order')
                    ->withTimestamps();
    }

    /**
     * Get all attendees registered for this session
     */
    public function registrations()
    {
        return $this->belongsToMany(Registration::class, 'registration_sessions')
                    ->withPivot('attendance_status')
                    ->withTimestamps();
    }
}
