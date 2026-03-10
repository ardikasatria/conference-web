<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'registration_id',
        'package_id',
        'user_id',
        'conference_id',
        'amount',
        'status',
        'bank_name',
        'account_number',
        'account_holder',
        'payment_invoice_number',
        'due_date',
        'confirmed_at',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'due_date' => 'datetime',
        'confirmed_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    /**
     * Get the registration
     */
    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }

    /**
     * Get the package
     */
    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    /**
     * Get the user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the conference
     */
    public function conference()
    {
        return $this->belongsTo(Conference::class);
    }

    /**
     * Get payment confirmations
     */
    public function confirmations()
    {
        return $this->hasMany(PaymentConfirmation::class);
    }

    /**
     * Get latest confirmation
     */
    public function latestConfirmation()
    {
        return $this->confirmations()->latest()->first();
    }

    /**
     * Check if payment is pending confirmation
     */
    public function isPendingConfirmation()
    {
        return $this->status === 'awaiting_confirmation';
    }

    /**
     * Mark as paid (by admin)
     */
    public function markAsPaid($notes = null)
    {
        $this->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        // Update registration payment status
        $this->registration->update(['payment_status' => 'paid']);

        return $this;
    }

    /**
     * Create payment confirmation request
     */
    public function requestConfirmation()
    {
        $this->update(['status' => 'awaiting_confirmation']);
        return $this;
    }
}
