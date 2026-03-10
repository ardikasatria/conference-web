<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Conference;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $payments = Payment::with(['user', 'conference', 'registration', 'package'])
            ->when($request->search, function ($q, $s) {
                $q->whereHas('user', fn($u) => $u->where('name', 'like', "%{$s}%"))
                  ->orWhere('payment_invoice_number', 'like', "%{$s}%");
            })
            ->when($request->conference_id, fn($q, $c) => $q->where('conference_id', $c))
            ->when($request->status, fn($q, $s) => $q->where('status', $s))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $conferences = Conference::orderBy('name')->get(['id', 'name']);

        return view('admin.payments.index', compact('payments', 'conferences'));
    }

    public function show(Payment $payment)
    {
        $payment->load(['user', 'conference', 'registration', 'package', 'confirmations']);
        return view('admin.payments.show', compact('payment'));
    }

    public function update(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,paid,confirmed,failed,refunded',
        ]);

        $payment->update($validated);

        if ($validated['status'] === 'confirmed') {
            $payment->update(['confirmed_at' => now()]);
        }

        return redirect()->route('admin.payments.index')->with('success', 'Payment status updated successfully.');
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();
        return redirect()->route('admin.payments.index')->with('success', 'Payment deleted successfully.');
    }
}
