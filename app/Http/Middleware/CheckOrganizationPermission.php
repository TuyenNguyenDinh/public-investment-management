<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckOrganizationPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ?string $permission): Response
    {
        $user = auth()->user();

        if ($user->hasOrganizationPermission($permission, session('organization_id'))) {
            return $next($request);
        }

        abort(Response::HTTP_FORBIDDEN);
    }
}
