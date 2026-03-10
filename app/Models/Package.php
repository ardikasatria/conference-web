<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Package extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'conference_id',
        'name',
        'description',
        'price',
        'max_capacity',
        'current_registered',
        'benefits',
        'status',
        'order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'benefits' => 'array',
    ];

    /**
     * Get the conference this package belongs to
     */
    public function conference()
    {
        return $this->belongsTo(Conference::class);
    }

    /**
     * Get all features for this package
     */
    public function features()
    {
        return $this->hasMany(PackageFeature::class)->orderBy('order');
    }

    /**
     * Get all registrations using this package
     */
    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    /**
     * Check if package still has available capacity
     */
    public function hasAvailableCapacity()
    {
        if ($this->max_capacity === null) {
            return true;
        }

        return $this->current_registered < $this->max_capacity;
    }

    /**
     * Get remaining capacity
     */
    public function getRemainingCapacity()
    {
        if ($this->max_capacity === null) {
            return null; // Unlimited
        }

        return $this->max_capacity - $this->current_registered;
    }

    /**
     * Register participant to this package
     */
    public function registerParticipant(Registration $registration)
    {
        if (!$this->hasAvailableCapacity()) {
            throw new \Exception('Package capacity is full');
        }

        $registration->update(['package_id' => $this->id]);
        $this->increment('current_registered');

        return $registration;
    }

    /**
     * Unregister participant from this package
     */
    public function unregisterParticipant(Registration $registration)
    {
        if ($registration->package_id === $this->id) {
            $registration->update(['package_id' => null]);
            $this->decrement('current_registered');
        }

        return $registration;
    }
}
