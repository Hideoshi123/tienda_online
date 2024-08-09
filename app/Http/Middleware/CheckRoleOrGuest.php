<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRoleOrGuest
{
    public function handle(Request $request, Closure $next, $role)
    {
        if (Auth::check()) {
            if (Auth::user()->hasRole($role)) {
                return $next($request);
            }
            return redirect()->route('users.index');
        }
        return $next($request);
    }
}
