<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Adminauth
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guard('admin')->user()) {
            return $next($request);
        }
        if ($request->ajax() || $request->wantsJson()) {
            return response('Unauthorized.', 401);
        } else {
            return redirect(route('admin.login'));
        }
    }
}
