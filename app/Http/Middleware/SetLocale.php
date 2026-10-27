<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        // Default is Arabic. Guarded with hasSession() so this never fatals
        // even if it somehow runs before the session is initialized.
        $locale = 'ar';
        if ($request->hasSession()) {
            $locale = $request->session()->get('locale', 'ar');
        }
        App::setLocale($locale);
        return $next($request);
    }
}