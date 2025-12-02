<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $permission): Response
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        $user = Auth::user();

        // Check if user has the required permission
        if ($user->hasPermission($permission)) {
            return $next($request);
        }

        // Optional: Allow 'Admin' role to bypass everything if needed, 
        // but we are sticking to explicit permissions as per plan.
        // However, for 'Role Management', we need to ensure Admin has access.
        // The seeder assigned 'view_roles', etc. to Admin, so it should work.

        abort(403, 'Unauthorized action.');
    }
}
