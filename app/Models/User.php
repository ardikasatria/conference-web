<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get all conference registrations for this user
     */
    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    /**
     * Get all conferences this user attended/registered for
     */
    public function conferences()
    {
        return $this->belongsToMany(Conference::class, 'registrations');
    }

    /**
     * Get all roles for this user
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles')
                    ->withPivot('conference_id')
                    ->withTimestamps();
    }

    /**
     * Get roles for a specific conference
     */
    public function rolesInConference($conferenceId)
    {
        return $this->roles()
                    ->wherePivot('conference_id', $conferenceId)
                    ->get()
                    ->pluck('name')
                    ->toArray();
    }

    /**
     * Check if user has a specific role (globally or in a conference)
     */
    public function hasRole($roleName, $conferenceId = null)
    {
        $query = $this->roles();
        
        if ($conferenceId) {
            $query->wherePivot('conference_id', $conferenceId);
        }
        
        return $query->where('roles.name', $roleName)->exists();
    }

    /**
     * Check if user is admin (global admin)
     */
    public function isAdmin($conferenceId = null)
    {
        return $this->hasRole('admin', $conferenceId);
    }

    /**
     * Check if user is reviewer
     */
    public function isReviewer($conferenceId = null)
    {
        return $this->hasRole('reviewer', $conferenceId);
    }

    /**
     * Check if user is participant
     */
    public function isParticipant($conferenceId = null)
    {
        return $this->hasRole('participant', $conferenceId);
    }

    /**
     * Assign a role to user
     */
    public function assignRole(Role $role, Conference $conference = null)
    {
        $this->roles()->attach($role->id, [
            'conference_id' => $conference ? $conference->id : null,
        ]);

        return $this;
    }

    /**
     * Remove a role from user
     */
    public function removeRole(Role $role, Conference $conference = null)
    {
        $query = $this->roles();
        
        if ($conference) {
            $query->wherePivot('conference_id', $conference->id);
        }
        
        $query->detach($role->id);

        return $this;
    }

    /**
     * Get all reviewer applications
     */
    public function reviewerApplications()
    {
        return $this->hasMany(ReviewerApplication::class);
    }

    /**
     * Get reviewer applications reviewed by this user (admin)
     */
    public function reviewedApplications()
    {
        return $this->hasMany(ReviewerApplication::class, 'reviewed_by');
    }

    /**
     * Get all paper reviews assigned to this user as reviewer
     */
    public function paperReviewsAsReviewer()
    {
        return $this->hasMany(PaperReview::class, 'reviewer_id');
    }

    /**
     * Get reviewed applications approved by this user (admin)
     */
    public function approvedApplications()
    {
        return $this->hasMany(ReviewerApplication::class, 'reviewed_by')
                    ->where('status', 'approved');
    }
}
