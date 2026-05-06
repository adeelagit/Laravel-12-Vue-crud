<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $permission): Response
    {
        if (!auth('admin')->check()) {
            return redirect()->route('admin.login');
        }

        if (!auth('admin')->user()->hasPermissionTo($permission, 'admin')) {
            abort(403, 'Unauthorized access to this resource');
        }

        return $next($request);
    }
}
