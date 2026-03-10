<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\PaymentConfirmation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $payments = Payment::with(['conference', 'registration', 'package'])
            ->where('user_id', Auth::id())
            ->when($request->status, fn($q, $s) => $q->where('status', $s))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('participant.payments.index', compact('payments'));
    }

    public function show(Payment $payment)
    {
        if ($payment->user_id !== Auth::id()) {
            abort(403);
        }

        $payment->load(['conference', 'registration', 'package', 'confirmations']);
        return view('participant.payments.show', compact('payment'));
    }

    public function uploadProof(Request $request, Payment $payment)
    {
        if ($payment->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'bank_name' => 'required|string|max:255',
            'sender_name' => 'required|string|max:255',
            'reference_number' => 'nullable|string|max:255',
            'amount_transferred' => 'required|numeric|min:0',
            'proof_image' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'notes' => 'nullable|string',
        ]);

        $proofPath = $request->file('proof_image')->store('payment-proofs', 'public');

        PaymentConfirmation::create([
            'payment_id' => $payment->id,
            'registration_id' => $payment->registration_id,
            'user_id' => Auth::id(),
            'conference_id' => $payment->conference_id,
            'bank_name' => $validated['bank_name'],
            'sender_name' => $validated['sender_name'],
            'transaction_date' => now(),
            'reference_number' => $validated['reference_number'],
            'amount_transferred' => $validated['amount_transferred'],
            'proof_image_path' => $proofPath,
            'notes' => $validated['notes'],
            'status' => 'pending',
        ]);

        $payment->update(['status' => 'pending']);

        return redirect()->route('participant.payments.index')->with('success', 'Payment proof uploaded successfully. Awaiting admin confirmation.');
    }
}
