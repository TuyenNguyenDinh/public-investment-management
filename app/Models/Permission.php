<?php

namespace App\Models;

use App\Models\Scopes\CheckOrganizationScope;
use App\Traits\SetDateTimeConfigTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Contracts\Permission as PermissionContract;
use Spatie\Permission\Exceptions\PermissionAlreadyExists;
use Spatie\Permission\Guard;
use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    use SetDateTimeConfigTrait;

    protected $fillable = [
        'name',
        'parent_id',
        'guard_name',
    ];

    protected static function booted(): void
    {
        if (Auth::check()) {
            static::addGlobalScope(new CheckOrganizationScope());
        }
    }

    /**
     * @param array $attributes
     * @return PermissionContract|SpatiePermission
     *
     */
    public static function create(array $attributes = []): PermissionContract|SpatiePermission
    {
        $attributes['guard_name'] = $attributes['guard_name'] ?? Guard::getDefaultName(static::class);

        if (!isset($attributes['parent_id'])) {
            $permission = static::getPermission(['name' => $attributes['name'], 'guard_name' => $attributes['guard_name']]);

            if ($permission) {
                throw PermissionAlreadyExists::create($attributes['name'], $attributes['guard_name']);
            }
        }

        return static::query()->create($attributes);
    }

    public function childPermission(): HasMany
    {
        return $this->hasMany(static::class, 'parent_id');
    }

    public function organizations(): BelongsToMany
    {
        return $this->belongsToMany(OrganizationUnit::class, PermissionOrganization::class, 'permission_id', 'organization_id');
    }

    /**
     * Check role with organization
     *
     * @param Builder $query
     * @param array|string $roleIds
     * @param string|null $organizationId
     * @return Builder
     */
    public function scopeCheckRoleOrganization(Builder $query, array|string $roleIds, ?string $organizationId = null): Builder
    {
        return $query->join('role_has_permissions AS rhp', 'permissions.id', '=', 'rhp.permission_id')
            ->join('roles_organizations AS ro', 'rhp.role_id', '=', 'ro.role_id')
            ->join('permissions_organizations AS po', 'permissions.id', '=', 'po.permission_id')
            ->when(is_array($roleIds), function (Builder $query) use ($roleIds) {
                $query->whereIn('ro.role_id', $roleIds);
            })
            ->when(is_string($roleIds), function (Builder $query) use ($roleIds) {
                $query->where('ro.role_id', $roleIds);
            })
            ->where([
                'ro.organization_id' => $organizationId,
                'po.organization_id' => $organizationId,
            ]);
    }
}
