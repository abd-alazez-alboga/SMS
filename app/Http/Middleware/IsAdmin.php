<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    public function handle(Request $request, Closure $next)
    {

        // Check if the user is authenticated
        if (Auth::check()) {
            /** @var \App\Models\User|null $user */
            $user = Auth::user();
            // Check if the user is an admin
            if ($user->isAdmin()) {
                return $next($request);
            } else {
                // User is not an admin
                return response()->json(['message' => 'Forbidden: You do not have access to this resource.'], 403);
            }
        } else {
            // User is not authenticated
            return response()->json(['message' => 'Unauthorized: Please log in.'], 401);
        }
    }
}
