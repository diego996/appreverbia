<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePasswordChanged
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return $next($request);
        }

        if ($user->password_changed_at) {
            return $next($request);
        }

        if ($request->routeIs('password.first-change') || $request->routeIs('logout')) {
            return $next($request);
        }

        return redirect()->route('password.first-change');
    }
}

