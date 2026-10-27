<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RequireSubscription
{
    public function handle(Request $request, Closure $next)
    {
        $student = auth()->user()->student;

        // Active subscription OR at least one lecture unlocked by an access code.
        if (!$student || !$student->hasPortalAccess()) {
            return redirect()->route('portal.subscription')
                ->with('info', 'يجب تفعيل الاشتراك أو إدخال كود فتح للوصول إلى هذا المحتوى.');
        }

        return $next($request);
    }
}