<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Models\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionController extends Controller
{
    public function index(Request $request)
    {
        $registrations = Registration::with('sessions.conference')
            ->where('user_id', Auth::id())
            ->where('status', 'confirmed')
            ->get();

        $registeredSessionIds = $registrations->flatMap(fn($r) => $r->sessions->pluck('id'))->unique();

        $sessions = Session::with(['conference', 'speakers'])
            ->whereIn('id', $registeredSessionIds)
            ->when($request->search, fn($q, $s) => $q->where('title', 'like', "%{$s}%"))
            ->orderBy('start_time')
            ->paginate(15)
            ->withQueryString();

        return view('participant.sessions.index', compact('sessions'));
    }
}
