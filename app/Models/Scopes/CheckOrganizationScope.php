<?php

namespace App\Models\Scopes;

use App\Helpers\Helpers;
use App\Models\OrganizationUnit;
use App\Models\Permission;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Psr\SimpleCache\InvalidArgumentException;

class CheckOrganizationScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     * @throws InvalidArgumentException
     */
    public function apply(Builder $builder, Model $model, array|string|null $otherOrganizations = null): void
    {
        if (session()->has('organization_id')) {
            $organizationIds = $this->getOrganizationIds();
            $builder->whereHas('organizations', function (Builder $query) use ($organizationIds, $otherOrganizations) {
                $query->whereIn('organization_id', $organizationIds)
                ->when(!empty($otherOrganizations), function (Builder $query) use ($otherOrganizations) {
                    $query->whereIn('organization_id', $otherOrganizations);
                });
            })
              ->when($model instanceof Permission, function ($query) use ($model) {
                $query->orWhereNull('parent_id');
            });
        }
    }

    /**
     * @throws InvalidArgumentException
     */
    private function getOrganizationIds()
    {
        $cacheKey = sprintf(config('cache.cache_key_list.user_organization_ids'),
            session('organization_id'),
            auth('web')->id()
        );
        $cacheOrganizationIds = Helpers::readCache($cacheKey);
            
        if (!$cacheOrganizationIds) {
            $cacheOrganizationIds = OrganizationUnit::query()
                ->descendantsAndSelf(session('organization_id'))
                ->pluck('id')->toArray();
            Helpers::writeCache($cacheKey, $cacheOrganizationIds);

        }
        return $cacheOrganizationIds;
    }
}
