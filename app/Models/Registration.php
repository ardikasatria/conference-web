<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Registration extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'conference_id',
        'user_id',
        'package_id',
        'submission_id',
        'ticket_number',
        'status',
        'submission_status',
        'payment_status',
        'registered_at',
        'payment_date',
        'amount_paid',
        'payment_method',
        'invoice_number',
        'notes',
    ];

    protected $casts = [
        'registered_at' => 'datetime',
        'payment_date' => 'datetime',
    ];

    /**
     * Get the conference for this registration
     */
    public function conference()
    {
        return $this->belongsTo(Conference::class);
    }

    /**
     * Get the user for this registration
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the package for this registration
     */
    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    /**
     * Get all sessions registered for this registration
     */
    public function sessions()
    {
        return $this->belongsToMany(Session::class, 'registration_sessions')
                    ->withPivot('attendance_status')
                    ->withTimestamps();
    }

    /**
     * Get the submission
     */
    public function submission()
    {
        return $this->belongsTo(Submission::class);
    }

    /**
     * Get the payment for this registration
     */
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    /**
     * Get payment confirmations
     */
    public function paymentConfirmations()
    {
        return $this->hasMany(PaymentConfirmation::class);
    }

    /**
     * Check if submission is required (has submitted paper/abstract)
     */
    public function requiresSubmission()
    {
        return $this->submission_status !== 'not_required';
    }

    /**
     * Check if payment is required
     */
    public function requiresPayment()
    {
        return $this->payment_status !== 'not_required' && $this->amount_paid > 0;
    }

    /**
     * Check if registration is complete (all requirements met)
     */
    public function isComplete()
    {
        $submissionDone = !$this->requiresSubmission() || $this->submission_status === 'approved';
        $paymentDone = !$this->requiresPayment() || $this->payment_status === 'paid';

        return $submissionDone && $paymentDone;
    }

    /**
     * Get registration progress (percentage)
     */
    public function getProgressPercentage()
    {
        $steps = 0;
        $completed = 0;

        // Registered step
        $steps++;
        $completed++;

        // Abstract submission step
        if ($this->requiresSubmission()) {
            $steps++;
            if ($this->submission_status === 'approved') {
                $completed++;
            } elseif ($this->submission_status === 'pending_review') {
                $completed += 0.5;
            }
        }

        // Payment step
        if ($this->requiresPayment()) {
            $steps++;
            if ($this->payment_status === 'paid') {
                $completed++;
            } elseif ($this->payment_status === 'awaiting_confirmation') {
                $completed += 0.5;
            }
        }

        return ($completed / $steps) * 100;
    }
}
