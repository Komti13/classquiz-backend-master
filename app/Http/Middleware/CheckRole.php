<?php

namespace App\Http\Middleware;

use Closure;

class CheckRole
{
    /**
     * Handle the incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param array $roles
     * @return mixed
     */
    public function handle($request, Closure $next, ...$roles)
    {

        if (!$request->user()->hasAnyRole($roles)) {
            return response()->json(['message' => 'Unauthorized.'], 401);
        }

        return $next($request);
    }

}
