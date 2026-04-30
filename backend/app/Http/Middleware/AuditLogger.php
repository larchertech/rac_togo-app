<?php

namespace App\Http\Middleware;

use App\Models\AuditLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuditLogger
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($request->user() && in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            $actionsCritiques = ['vote', 'candidature', 'election', 'alumni', 'cotisation'];
            $path = $request->path();

            foreach ($actionsCritiques as $action) {
                if (str_contains($path, $action)) {
                    AuditLog::create([
                        'user_id' => $request->user()->id,
                        'action' => $request->method() . '.' . $action,
                        'entite' => ucfirst($action),
                        'entite_id' => $request->route()->parameter('id'),
                        'data' => $request->except(['password', 'otp_code']),
                        'ip_address' => $request->ip(),
                        'user_agent' => $request->userAgent(),
                    ]);
                    break;
                }
            }
        }

        return $response;
    }
}
