<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        $user = Auth::user();

        // Check if user has one of the required roles
        // Assuming the user model has a 'role' column which is a string
        // If roles are pipe-separated in the argument, explode them

        // If $roles is empty, it means no specific role required (just auth), but middleware name implies role check.
        // Usually passed as role:admin,super admin

        // In Laravel middleware parameters: handle($request, $next, $role1, $role2, ...)
        // So $roles is an array of arguments passed after the colon.

        // If the first argument contains pipes, explode it (common convention)
        if (isset($roles[0]) && str_contains($roles[0], '|')) {
            $roles = explode('|', $roles[0]);
        }

        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // If user is super admin, allow everything (optional, but good practice)
        if ($user->role === 'super admin') {
            return $next($request);
        }

        abort(403, 'Unauthorized action.');
    }
}
