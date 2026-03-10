<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
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
    public function index(Request $request): RedirectResponse
    {
        $user = Auth::user();

        // Check global admin role first (assigned without conference_id)
        if ($user->hasRole('admin')) {
            return redirect()->route('dashboard.admin');
        }

        // Then check conference-scoped roles
        $conferenceId = $request->query('conference') ?? session('current_conference_id');

        if ($conferenceId) {
            $roles = $user->rolesInConference($conferenceId);

            if (in_array('reviewer', $roles)) {
                return redirect()->route('dashboard.reviewer');
            }

            if (in_array('participant', $roles)) {
                return redirect()->route('dashboard.participant');
            }
        }

        // Fallback: check if user has any global role
        if ($user->hasRole('reviewer')) {
            return redirect()->route('dashboard.reviewer');
        }

        if ($user->hasRole('participant')) {
            return redirect()->route('dashboard.participant');
        }

        // Default: send to participant dashboard as safe fallback
        return redirect()->route('dashboard.participant');
    }

    public function admin(): View
    {
        return view('dashboard.admin');
    }

    public function participant(): View
    {
        return view('dashboard.participant');
    }

    public function reviewer(): View
    {
        return view('dashboard.reviewer');
    }
}
