<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!$request->user() || !in_array(strtolower($request->user()->role), array_map('strtolower', $roles))) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized Access Privileges.'], 403);
            }
            return redirect('/login')->with('error', 'Unauthorized access.');
        }

        return $next($request);
    }
}