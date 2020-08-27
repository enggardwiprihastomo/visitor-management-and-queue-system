<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckLevelAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $level = null)
    {
        if (! Auth::check()) {
            abort(404);
        }
        if ($level == 'admin') {
            if (! Auth::user()->is_admin) {
                abort(404);
            }
        }
        else if ($level == 'staff') {
            $permission = session()->has('terminal') && session()->has('counter');
            if (! $permission) {
                abort(404);
            }
        }
		else if ($level == 'registrasi') {
            $permission = ! Auth::user()->is_admin && ! Auth::user()->is_staff;
            if (! $permission) {
                abort(404);
            }
        }

        return $next($request);
    }
}
