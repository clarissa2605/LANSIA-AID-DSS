<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RespondenMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user()) {
            return response()->json([
                'message' => 'Unauthenticated'
            ], 401);
        }

        if ($request->user()->role !== 'responden') {
            return response()->json([
                'message' => 'Access denied. Responden only.'
            ], 403);
        }

        return $next($request);
    }
}
