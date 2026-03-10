<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\Conference;
use App\Models\Registration;
use App\Models\Submission;
use App\Models\PaperReview;
use App\Models\Payment;
use App\Models\ReviewerApplication;
use App\Models\User;

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

    /**
     * Admin dashboard with real-time statistics.
     */
    public function admin(): View
    {
        // Get the latest/active conference
        $conference = Conference::latest('start_date')->first();

        // Statistics
        $totalRegistrations = Registration::count();
        $totalSubmissions = Submission::count();
        $pendingReviews = PaperReview::where('status', 'pending')->count();
        $totalRevenue = Payment::where('status', 'paid')->sum('amount');
        $pendingApplications = ReviewerApplication::where('status', 'pending')->count();
        $totalUsers = User::count();
        $totalReviewers = User::whereHas('roles', fn($q) => $q->where('roles.name', 'reviewer'))->count();

        // Recent registrations (latest 5)
        $recentRegistrations = Registration::with(['user', 'conference'])
            ->latest()
            ->take(5)
            ->get();

        // Recent submissions (latest 5)
        $recentSubmissions = Submission::with(['user', 'conference'])
            ->latest()
            ->take(5)
            ->get();

        // Pending reviewer applications (latest 5)
        $pendingReviewerApps = ReviewerApplication::with(['user', 'conference'])
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.admin', compact(
            'conference',
            'totalRegistrations',
            'totalSubmissions',
            'pendingReviews',
            'totalRevenue',
            'pendingApplications',
            'totalUsers',
            'totalReviewers',
            'recentRegistrations',
            'recentSubmissions',
            'pendingReviewerApps'
        ));
    }

    /**
     * Participant dashboard with user-specific data.
     */
    public function participant(): View
    {
        $user = Auth::user();

        // Get user's registrations with related data
        $registrations = Registration::with(['conference', 'package', 'payment', 'submission'])
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        // Get user's submissions
        $submissions = Submission::with(['conference', 'paperReviews'])
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        // Get user's payments
        $payments = Payment::with(['conference', 'registration', 'package'])
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        // Get user's registered sessions (through registrations)
        $sessions = collect();
        foreach ($registrations as $reg) {
            $regSessions = $reg->sessions()->with('speakers')->get();
            $sessions = $sessions->merge($regSessions);
        }
        $sessions = $sessions->unique('id')->sortBy('start_time');

        // Active conference
        $conference = Conference::latest('start_date')->first();

        // Quick stats
        $activeRegistration = $registrations->first();
        $submissionCount = $submissions->count();
        $pendingPayment = $payments->where('status', '!=', 'paid')->first();
        $sessionCount = $sessions->count();

        return view('dashboard.participant', compact(
            'user',
            'registrations',
            'submissions',
            'payments',
            'sessions',
            'conference',
            'activeRegistration',
            'submissionCount',
            'pendingPayment',
            'sessionCount'
        ));
    }

    /**
     * Reviewer dashboard with review assignments and statistics.
     */
    public function reviewer(): View
    {
        $user = Auth::user();

        // Get all paper reviews assigned to this reviewer
        $paperReviews = PaperReview::with(['submission', 'conference', 'grades'])
            ->where('reviewer_id', $user->id)
            ->latest()
            ->get();

        // Statistics
        $totalAssigned = $paperReviews->count();
        $completedReviews = $paperReviews->whereIn('status', ['completed', 'accepted', 'rejected'])->count();
        $pendingReviews = $paperReviews->whereIn('status', ['pending', 'in_progress'])->count();

        // Pending reviews (need action)
        $pendingPapers = $paperReviews->whereIn('status', ['pending', 'in_progress']);

        // Completed reviews (history)
        $completedPapers = $paperReviews->whereIn('status', ['completed', 'accepted', 'rejected']);

        // Reviewer application & expert topics
        $reviewerApplication = ReviewerApplication::with('topics')
            ->where('user_id', $user->id)
            ->where('status', 'approved')
            ->latest()
            ->first();

        $expertTopics = $reviewerApplication ? $reviewerApplication->topics : collect();

        // Active conference
        $conference = Conference::latest('start_date')->first();

        // Next deadline (closest review with pending status)
        $nextDeadline = null;
        if ($conference && $conference->end_date) {
            $nextDeadline = $conference->end_date;
        }

        return view('dashboard.reviewer', compact(
            'user',
            'paperReviews',
            'totalAssigned',
            'completedReviews',
            'pendingReviews',
            'pendingPapers',
            'completedPapers',
            'reviewerApplication',
            'expertTopics',
            'conference',
            'nextDeadline'
        ));
    }
}
