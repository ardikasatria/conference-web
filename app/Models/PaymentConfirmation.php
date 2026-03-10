<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentConfirmation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'payment_id',
        'registration_id',
        'user_id',
        'conference_id',
        'bank_name',
        'sender_name',
        'transaction_date',
        'reference_number',
        'amount_transferred',
        'proof_image_path',
        'notes',
        'status',
        'admin_notes',
        'verified_by',
        'verified_at',
    ];

    protected $casts = [
        'amount_transferred' => 'decimal:2',
        'verified_at' => 'datetime',
    ];

    /**
     * Get the payment
     */
    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    /**
     * Get the registration
     */
    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }

    /**
     * Get the user who submitted confirmation
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
     * Get the admin who verified
     */
    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Approve confirmation (by admin)
     */
    public function approve($notes = null)
    {
        $this->update([
            'status' => 'approved',
            'admin_notes' => $notes,
            'verified_by' => auth()->id(),
            'verified_at' => now(),
        ]);

        // Mark payment as paid
        $this->payment->markAsPaid($notes);

        return $this;
    }

    /**
     * Reject confirmation (by admin)
     */
    public function reject($notes = null)
    {
        $this->update([
            'status' => 'rejected',
            'admin_notes' => $notes,
            'verified_by' => auth()->id(),
            'verified_at' => now(),
        ]);

        // Reset payment status back to pending
        $this->payment->update(['status' => 'pending']);

        return $this;
    }
}
