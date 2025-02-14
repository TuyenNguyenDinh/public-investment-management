<?php

namespace App\Models\Scopes;

use App\Models\OrganizationUnit;
use App\Models\Permission;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Log;

class CheckOrganizationScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model, array|string|null $otherOrganizations = null): void
    {
        if (session()->has('organization_id')) {
            $organizationIds = OrganizationUnit::query()->descendantsAndSelf(session('organization_id'))->pluck('id')->toArray();
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
}
