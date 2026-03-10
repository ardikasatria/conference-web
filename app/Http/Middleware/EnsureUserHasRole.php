<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role, ?string $conference = null): Response
    {
        // If user is not authenticated
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        $conferenceId = $conference ?: session('current_conference_id');

        // Check if user has the required role
        if ($conferenceId) {
            // Check role in specific conference
            if (!$user->hasRole($role, $conferenceId)) {
                abort(403, 'Unauthorized');
            }
        } else {
            // Check if user has role in any conference
            if (!$user->roles()->where('name', $role)->exists()) {
                abort(403, 'Unauthorized');
            }
        }

        return $next($request);
    }
}
