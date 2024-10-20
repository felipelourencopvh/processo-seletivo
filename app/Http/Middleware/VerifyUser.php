<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerifyUser
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::user() && Auth::user()->hasAnyRole('super_admin', 'admin')) {
            return $next($request);
        }

        return redirect('/');
    }
}
