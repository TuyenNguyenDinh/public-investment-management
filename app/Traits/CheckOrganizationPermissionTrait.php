<?php

namespace App\Traits;

use App\Helpers\Helpers;
use App\Models\OrganizationUnit;
use Illuminate\Support\Facades\Auth;
use Psr\SimpleCache\InvalidArgumentException;

trait CheckOrganizationPermissionTrait
{
    /**
     * Check if the current user has the specified organization permission.
     *
     * This function retrieves the user's permissions from the cache and checks
     * if the given permission exists within the cached permissions.
     *
     * @param string $permission The permission to check for.
     * @return bool True if the user has the permission, false otherwise.
     * @throws InvalidArgumentException
     */
    public function checkHasOrganizationPermission(string $permission): bool
    {
        $cacheKey = sprintf(config('cache.cache_key_list.user_permission_list'),
            session('organization_id'),
            auth('web')->id()
        );
        $permissionCache = Helpers::readCache($cacheKey) ?? [];

        return in_array($permission, $permissionCache);
    }
}
