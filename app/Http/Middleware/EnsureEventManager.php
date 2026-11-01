<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureEventManager
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user || !in_array($user->role, [
            'admin',
            'staff',
            'club_advisor',
        ])) {
    return response()->json([
    'message' => 'You are not authorized to manage events.',
], 403);
        }

        return $next($request);
    }
}
