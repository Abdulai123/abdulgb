<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Check if the user has one of the specified roles
        if ($request->user() && in_array($request->user()->role, $roles)) {
            return $next($request);
        }

        // Redirect or respond with an error if the user doesn't have the required role
        return abort(404);
    }
}
