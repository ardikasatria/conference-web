<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Conference extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'start_date',
        'end_date',
        'location',
        'image',
        'slug',
        'status',
        'capacity',
        'registration_fee',
        'contact_email',
        'contact_phone',
        'terms_conditions',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    /**
     * Get all sessions for this conference
     */
    public function sessions()
    {
        return $this->hasMany(Session::class);
    }

    /**
     * Get all registrations for this conference
     */
    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    /**
     * Get all packages for this conference
     */
    public function packages()
    {
        return $this->hasMany(Package::class)->orderBy('order');
    }

    /**
     * Get active packages only
     */
    public function activePackages()
    {
        return $this->packages()->where('status', 'active');
    }

    /**
     * Get all registered users
     */
    public function attendees()
    {
        return $this->belongsToMany(User::class, 'registrations');
    }

    /**
     * Get all reviewer applications for this conference
     */
    public function reviewerApplications()
    {
        return $this->hasMany(ReviewerApplication::class);
    }

    /**
     * Get pending reviewer applications
     */
    public function pendingReviewerApplications()
    {
        return $this->reviewerApplications()->where('status', 'pending');
    }

    /**
     * Get users with a specific role in this conference
     */
    public function usersWithRole($roleName)
    {
        return $this->belongsToMany(User::class, 'registrations')
                    ->whereHas('roles', function($q) use ($roleName) {
                        $q->where('roles.name', $roleName)
                          ->wherePivot('conference_id', $this->id);
                    });
    }

    /**
     * Get all topics for this conference
     */
    public function topics()
    {
        return $this->belongsToMany(Topic::class, 'conference_topics')->orderBy('order');
    }

    /**
     * Get all grading criteria for this conference
     */
    public function gradingCriteria()
    {
        return $this->hasMany(GradingCriteria::class)->orderBy('order');
    }

    /**
     * Get all paper reviews for this conference
     */
    public function paperReviews()
    {
        return $this->hasMany(PaperReview::class);
    }
}
