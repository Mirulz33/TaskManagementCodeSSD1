<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth; // Import Auth facade
use App\Models\User; // Optional: for type hinting

class AdminOnly
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get authenticated user with type hint for Intelephense
        /** @var User|null $user */
        $user = Auth::user();

        // Check if user is not logged in or not an admin
        if (!$user || $user->role !== 'admin') {
            abort(403, 'Admin access only');
        }

        return $next($request);
    }
}
