<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IdleTimeout
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $lastActivity = session('last_activity_time');
            // Timeout in seconds: defaults to 900 seconds (15 minutes)
            $timeout = config('auth.idle_timeout', 900); 

            if ($lastActivity && (time() - $lastActivity > $timeout)) {
                Auth::logout();
                session()->forget('last_activity_time');
                session()->invalidate();
                session()->regenerateToken();

                return redirect()->route('login')->with('error', 'Sesi Anda telah berakhir karena tidak ada aktivitas.');
            }

            session(['last_activity_time' => time()]);
        }

        return $next($request);
    }
}
