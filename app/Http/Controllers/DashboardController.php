<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;

/**
 * Handles dashboard routing based on user roles.
 * Single Responsibility: role-based dashboard page rendering.
 */
class DashboardController extends Controller
{
    /**
     * Route to the appropriate dashboard based on user role.
     */
    public function index(Request $request): Response|RedirectResponse
    {
        $user = Auth::user();
        $conferenceId = $request->query('conference') ?? session('current_conference_id');
        $roles = $user->rolesInConference($conferenceId);

        if (in_array('admin', $roles)) {
            return redirect()->route('dashboard.admin');
        }

        if (in_array('reviewer', $roles)) {
            return redirect()->route('dashboard.reviewer');
        }

        if (in_array('participant', $roles)) {
            return redirect()->route('dashboard.participant');
        }

        return redirect()->route('home')
            ->with('message', 'You do not have access to the dashboard.');
    }

    public function admin(): Response
    {
        return Inertia::render('Dashboard/Admin');
    }

    public function participant(): Response
    {
        return Inertia::render('Dashboard/Participant');
    }

    public function reviewer(): Response
    {
        return Inertia::render('Dashboard/Reviewer');
    }
}
