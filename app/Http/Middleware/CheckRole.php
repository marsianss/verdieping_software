<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Role;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $roleName): Response
    {
        // Check if user is logged in
        if (!$request->user()) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Unauthorized.'], 403);
            }

            return redirect()->route('login');
        }

        // Get the user
        $user = $request->user();

        // If the role relationship hasn't been loaded, load it
        if (!$user->relationLoaded('role')) {
            $user->load('role');
        }

        // Check if user has the required role
        if (!$user->role || $user->role->name !== $roleName) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Unauthorized.'], 403);
            }

            abort(403, 'Unauthorized action. This page is only for ' . $roleName . 's.');
        }

        return $next($request);
    }
}
