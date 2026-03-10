<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Show the appropriate dashboard based on user role
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Get the current conference (could be passed as parameter)
        $conferenceId = $request->query('conference') ?? session('current_conference_id');
        
        // Get user's roles in the conference
        $roles = $user->rolesInConference($conferenceId);

        // Redirect based on role - admin takes priority
        if (in_array('admin', $roles)) {
            return $this->showAdminDashboard();
        } elseif (in_array('reviewer', $roles)) {
            return $this->showReviewerDashboard();
        } elseif (in_array('participant', $roles)) {
            return $this->showParticipantDashboard();
        }

        // If no suitable role found
        return redirect('/')->with('message', 'You do not have access to the dashboard.');
    }

    /**
     * Show admin dashboard
     */
    public function showAdminDashboard()
    {
        return view('dashboard.admin');
    }

    /**
     * Show participant dashboard
     */
    public function showParticipantDashboard()
    {
        return view('dashboard.participant');
    }

    /**
     * Show reviewer dashboard
     */
    public function showReviewerDashboard()
    {
        return view('dashboard.reviewer');
    }
}
