<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Psr\SimpleCache\InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response;

class CheckOrganizationPermission
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(Request): (Response) $next
     * @throws InvalidArgumentException
     */
    public function handle(Request $request, Closure $next, ?string $permission): Response
    {
        /* @var User $user */
        $user = auth()->user();

        if ($user->checkHasOrganizationPermission($permission)) {
            return $next($request);
        }

        abort(Response::HTTP_FORBIDDEN);
    }
}
