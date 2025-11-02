<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LogActivity
{
    /**
     * Handle an incoming request and log user activity
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only log authenticated user activities
        if (Auth::check()) {
            $user = Auth::user();
            
            // Prepare activity data
            $activity = [
                'timestamp' => now()->toDateTimeString(),
                'user_id' => $user->id,
                'user_name' => $user->name,
                'user_email' => $user->email,
                'user_role' => $user->roles->pluck('name')->implode(', ') ?: 'No Role',
                'method' => $request->method(),
                'url' => $request->fullUrl(),
                'path' => $request->path(),
                'route_name' => $request->route() ? $request->route()->getName() : 'N/A',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'status_code' => $response->getStatusCode(),
                'request_data' => $this->getCleanRequestData($request),
            ];

            // Log to user activity channel
            Log::channel('user_activity')->info('User Activity', $activity);
            
            // Log specific actions
            $this->logSpecificActions($request, $user, $activity);
        }

        return $response;
    }

    /**
     * Get cleaned request data (exclude sensitive information)
     *
     * @param Request $request
     * @return array
     */
    protected function getCleanRequestData(Request $request): array
    {
        $data = $request->except(['password', 'password_confirmation', '_token', '_method']);
        
        // Limit data size for logging
        if (count($data) > 0) {
            return array_slice($data, 0, 10);
        }
        
        return [];
    }

    /**
     * Log specific user actions with custom messages
     *
     * @param Request $request
     * @param $user
     * @param array $activity
     * @return void
     */
    protected function logSpecificActions(Request $request, $user, array $activity): void
    {
        $method = $request->method();
        $path = $request->path();

        // Log specific CRUD operations
        if ($method === 'POST' && str_contains($path, 'posts') && !str_contains($path, 'comments')) {
            Log::channel('user_activity')->info("User {$user->name} created a new post", $activity);
        } elseif ($method === 'PUT' && str_contains($path, 'posts')) {
            Log::channel('user_activity')->info("User {$user->name} updated a post", $activity);
        } elseif ($method === 'DELETE' && str_contains($path, 'posts')) {
            Log::channel('user_activity')->info("User {$user->name} deleted a post", $activity);
        } elseif ($method === 'POST' && str_contains($path, 'comments')) {
            Log::channel('user_activity')->info("User {$user->name} added a comment", $activity);
        } elseif ($method === 'DELETE' && str_contains($path, 'comments')) {
            Log::channel('user_activity')->info("User {$user->name} deleted a comment", $activity);
        } elseif (str_contains($path, 'login')) {
            Log::channel('user_activity')->info("User {$user->name} logged in", $activity);
        } elseif (str_contains($path, 'logout')) {
            Log::channel('user_activity')->info("User {$user->name} logged out", $activity);
        }
    }
}
