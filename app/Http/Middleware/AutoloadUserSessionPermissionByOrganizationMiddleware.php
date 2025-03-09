<?php

namespace App\Http\Middleware;

use App\Helpers\Helpers;
use App\Models\OrganizationUnit;
use App\Models\Permission;
use Closure;
use Illuminate\Http\Request;
use Psr\SimpleCache\InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response;

class AutoloadUserSessionPermissionByOrganizationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     * @throws InvalidArgumentException
     */
    public function handle(Request $request, Closure $next): Response
    {
        $loadedPermissions = [];
        $user = auth('web')->user();
        $cacheKey = sprintf(config('cache.cache_key_list.user_permission_list'),
            session('organization_id'), $user->id)
        ;
        $getCache = Helpers::readCache($cacheKey);
        $autoloadPermissions = session('autoload_permissions', false);
        $organizationId = session('organization_id');

        if (!$autoloadPermissions || is_null($getCache)) {
            $allPermissions = Permission::query()->withoutGlobalScopes()->whereNotNull('parent_id')
                ->select('id', 'name')
                ->pluck('name');

            $organizationAncestors = OrganizationUnit::query()
                ->withoutGlobalScopes()
                ->ancestorsAndSelf($organizationId)
                ->select('id')
                ->pluck('id')
                ->toArray();

            $userRoles = $user->roles()
                ->withoutGlobalScopes()
                ->whereHas('organizations', function ($query) use ($organizationAncestors) {
                    $query->whereIn('organization_id', $organizationAncestors);
                })
                ->cursor();

            foreach ($userRoles as $role) {
                foreach ($allPermissions as $permission) {
                    if ($role->checkPermissionTo($permission)) {
                        $loadedPermissions[] = $permission;
                    }
                }
            }
            Helpers::writeCache($cacheKey, $loadedPermissions);          
            session()->put('autoload_permissions', true);
        }
        return $next($request);
    }
}
