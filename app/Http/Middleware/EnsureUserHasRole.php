<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request and check user role
     *
     * @param Closure(Request): (Response) $next
     * @param string $role
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = Auth::user();
        
        // Check if user is authenticated
        if (!$user) {
            Log::channel('user_activity')->warning('Unauthenticated access attempt', [
                'timestamp' => now()->toDateTimeString(),
                'path' => $request->path(),
                'ip_address' => $request->ip(),
                'required_role' => $role,
            ]);
            
            abort(403, 'Unauthorized access. Please login first.');
        }

        // Check if user has required role
        if (!$user->hasRole($role)) {
            Log::channel('user_activity')->warning('Unauthorized role access attempt', [
                'timestamp' => now()->toDateTimeString(),
                'user_id' => $user->id,
                'user_name' => $user->name,
                'user_roles' => $user->roles->pluck('name')->implode(', '),
                'required_role' => $role,
                'path' => $request->path(),
                'ip_address' => $request->ip(),
            ]);
            
            abort(403, "Unauthorized. This action requires '{$role}' role.");
        }

        // Log successful role check
        Log::channel('user_activity')->info('Role check passed', [
            'timestamp' => now()->toDateTimeString(),
            'user_id' => $user->id,
            'user_name' => $user->name,
            'required_role' => $role,
            'path' => $request->path(),
        ]);

        return $next($request);
    }
}
