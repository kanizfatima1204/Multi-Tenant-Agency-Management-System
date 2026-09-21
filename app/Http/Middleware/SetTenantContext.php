<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetTenantContext
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (!$user) {
            return redirect()->route('login');
        }

        abort_if(! $user->isAdmin() && ! $user->tenant, 403, 'A tenant workspace is required for this account.');

        app()->instance('currentTenant', $user->tenant);

        return $next($request);
    }
}
