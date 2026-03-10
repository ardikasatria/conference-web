<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Models\Conference;
use App\Models\User;
use App\Models\Package;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    public function index(Request $request)
    {
        $registrations = Registration::with(['user', 'conference', 'package'])
            ->when($request->search, function ($q, $s) {
                $q->whereHas('user', fn($u) => $u->where('name', 'like', "%{$s}%")->orWhere('email', 'like', "%{$s}%"));
            })
            ->when($request->conference_id, fn($q, $c) => $q->where('conference_id', $c))
            ->when($request->status, fn($q, $s) => $q->where('status', $s))
            ->latest('registered_at')
            ->paginate(15)
            ->withQueryString();

        $conferences = Conference::orderBy('name')->get(['id', 'name']);

        return view('admin.registrations.index', compact('registrations', 'conferences'));
    }

    public function create()
    {
        $conferences = Conference::orderBy('name')->get(['id', 'name']);
        $users = User::orderBy('name')->get(['id', 'name', 'email']);
        $packages = Package::with('conference')->orderBy('name')->get();
        return view('admin.registrations.create', compact('conferences', 'users', 'packages'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'conference_id' => 'required|exists:conferences,id',
            'user_id' => 'required|exists:users,id',
            'package_id' => 'nullable|exists:packages,id',
            'status' => 'required|in:pending,confirmed,cancelled,waitlisted',
            'payment_status' => 'nullable|in:unpaid,partial,paid,refunded',
            'notes' => 'nullable|string',
        ]);

        $validated['ticket_number'] = 'TKT-' . strtoupper(uniqid());
        $validated['registered_at'] = now();

        Registration::create($validated);

        return redirect()->route('admin.registrations.index')->with('success', 'Registration created successfully.');
    }

    public function edit(Registration $registration)
    {
        $registration->load(['user', 'conference', 'package']);
        $conferences = Conference::orderBy('name')->get(['id', 'name']);
        $users = User::orderBy('name')->get(['id', 'name', 'email']);
        $packages = Package::with('conference')->orderBy('name')->get();
        return view('admin.registrations.edit', compact('registration', 'conferences', 'users', 'packages'));
    }

    public function update(Request $request, Registration $registration)
    {
        $validated = $request->validate([
            'conference_id' => 'required|exists:conferences,id',
            'user_id' => 'required|exists:users,id',
            'package_id' => 'nullable|exists:packages,id',
            'status' => 'required|in:pending,confirmed,cancelled,waitlisted',
            'payment_status' => 'nullable|in:unpaid,partial,paid,refunded',
            'notes' => 'nullable|string',
        ]);

        $registration->update($validated);

        return redirect()->route('admin.registrations.index')->with('success', 'Registration updated successfully.');
    }

    public function destroy(Registration $registration)
    {
        $registration->delete();
        return redirect()->route('admin.registrations.index')->with('success', 'Registration deleted successfully.');
    }
}
