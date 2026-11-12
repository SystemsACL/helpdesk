<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ForcePasswordChange
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        // Usuario desactivado mientras tenía sesión abierta.
        if ($user && ! $user->is_active) {
            Auth::logout();
            $request->session()->invalidate();
            return redirect()->route('login');
        }

        if ($user && $user->must_change_password) {
            return redirect()->route('password.edit');
        }

        return $next($request);
    }
}
