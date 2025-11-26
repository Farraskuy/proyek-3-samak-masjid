<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RestrictSuperAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user && $user->role === 'super admin') {
            // Allow GET requests (viewing data)
            if ($request->isMethod('get') || $request->isMethod('head')) {
                return $next($request);
            }

            // Allow Post Approval
            if ($request->routeIs('admin.postingan.approval.update')) {
                return $next($request);
            }

            // Allow Delete Data 
            if ($request->routeIs('admin.postingan.delete')) {
                return $next($request);
            }

            // Allow User Creation (if implemented)
            // Assuming route names for user creation might be 'admin.pengguna.store'
            if ($request->routeIs('admin.pengguna.store') || $request->routeIs('admin.pengguna.create')) {
                return $next($request);
            }

            // Allow Logout (usually POST)
            if ($request->routeIs('logout')) {
                return $next($request);
            }

            // Block everything else (POST, PUT, DELETE, PATCH)
            // "tidak dapat menambah data apapun"
            return abort(403, 'Super Admin tidak diizinkan menambah atau mengubah data ini.');
        }

        return $next($request);
    }
}
