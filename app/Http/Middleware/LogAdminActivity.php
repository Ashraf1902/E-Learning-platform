<?php

namespace App\Http\Middleware;

use App\Models\AdminActivityLog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LogAdminActivity
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'], true)) {
            try {
                AdminActivityLog::recordFromRequest($request, $response->getStatusCode());
            } catch (\Throwable $e) {
                // Never block an admin action just because the audit table is unavailable.
                Log::warning('Unable to write admin activity log: ' . $e->getMessage());
            }
        }

        return $response;
    }
}
