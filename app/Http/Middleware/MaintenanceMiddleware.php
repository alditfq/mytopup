<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Setting;
use Symfony\Component\HttpFoundation\Response;

class MaintenanceMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $isMaintenance = Setting::getVal('is_maintenance', 'false');

        if ($isMaintenance === 'true') {
            // Allow admin routes, auth triggers, or active logged-in admins to bypass
            if ($request->is('admin*') || 
                $request->is('logout') || 
                $request->is('login') || 
                (Auth::check() && Auth::user()->role === 'admin')) {
                return $next($request);
            }

            // Present the premium neomorphic maintenance warnings screen
            return response()->view('maintenance', [], 503);
        }

        return $next($request);
    }
}
