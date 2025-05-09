<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        $user = Auth::user();
        if ($user && $user->role == $role) {
            return $next($request);
        }

        return response()->json([
            "message" => "Unauthenticated."
        ], 401);
    }
}
