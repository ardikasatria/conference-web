<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'description',
    ];

    /**
     * Get all users with this role
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_roles')
                    ->withPivot('conference_id')
                    ->withTimestamps();
    }

    /**
     * Get users with this role in a specific conference
     */
    public function usersInConference(Conference $conference)
    {
        return $this->users()
                    ->wherePivot('conference_id', $conference->id);
    }
}
