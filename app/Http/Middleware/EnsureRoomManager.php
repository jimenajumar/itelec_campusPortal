<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRoomManager
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!in_array($request->user()->role, ['admin', 'staff'])) {
            return response()->json([
                'message' => 'You are not authorized to manage rooms.',
            ], 403);
        }

        return $next($request);
    }
}