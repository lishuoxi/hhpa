<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Events\QueryExecuted;

class RequestTiming
{
    public function handle(Request $request, Closure $next)
    {
        $start = microtime(true);
        $sqlTimeMs = 0.0;
        $queries = 0;

        // Listen for query executed events for this request
        Event::listen(QueryExecuted::class, $listener = function (QueryExecuted $event) use (&$sqlTimeMs, &$queries) {
            // $event->time is in milliseconds
            $sqlTimeMs += (float) $event->time;
            $queries++;
        });

        $response = $next($request);

        $durationMs = (microtime(true) - $start) * 1000.0;

        // Set header with total response time
        if (method_exists($response, 'headers')) {
            $response->headers->set('X-Response-Time', sprintf('%.2fms', $durationMs));
        }

        // Log request timing details
        try {
            Log::info('request.timing', [
                'method' => $request->getMethod(),
                'path' => $request->getPathInfo(),
                'status' => method_exists($response, 'getStatusCode') ? $response->getStatusCode() : null,
                'response_time_ms' => round($durationMs, 2),
                'sql_time_ms' => round($sqlTimeMs, 2),
                'queries' => $queries,
                'ip' => $request->ip(),
            ]);
        } catch (\Throwable $e) {
            // Swallow logging errors to not affect request
        }

        // Best-effort remove our listener to avoid leaking across requests
        try {
            Event::forget(QueryExecuted::class);
        } catch (\Throwable $e) {
            // ignore
        }

        return $response;
    }
}

