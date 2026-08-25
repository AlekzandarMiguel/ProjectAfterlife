<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        if (!$user->isActive()) {
            auth()->logout();
            return redirect()->route('login')->withErrors(['email' => 'Your account has been suspended. Please contact administrator.']);
        }

        if (!in_array($user->role->value, $roles)) {
            abort(403, 'Unauthorized access: You do not possess the required system role.');
        }

        return $next($request);
    }
}
