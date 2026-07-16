<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrainerAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('trainer')->check()) {
            return redirect()->route('login');
        }
        return $next($request);
    }
}