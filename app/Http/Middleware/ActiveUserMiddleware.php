<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ActiveUserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!$request->user()) {
            return response()->json([
                'message' => 'Unauthenticated.'
            ], 401);
        }

        // Check if user is active
        if (!$request->user()->active) {
            // Revoke the current token since the user is not active
            $request->user()->currentAccessToken()->delete();
            
            return response()->json([
                'message' => 'Your account is not active. Please contact an administrator.',
                'error' => 'account_inactive'
            ], 403);
        }

        return $next($request);
    }
}
