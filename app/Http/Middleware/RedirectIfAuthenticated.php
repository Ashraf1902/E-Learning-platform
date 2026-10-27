<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, string ...$guards): mixed
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();

                // Redirect based on role instead of always going to /dashboard
                if ($user->isAdmin()) {
                    return redirect()->route('dashboard');
                }

                return redirect()->route('portal.dashboard');
            }
        }

        return $next($request);
    }
}