<?php

namespace App\Services\Organizations;

use App\Models\Role;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class GetRoleByOrganizationIdsService
{
    /**
     * Get roles by organization ids
     *
     * @param string $ids
     * @return Collection
     */
    public function run(string $ids): Collection
    {
        $ids = explode(',', $ids);
        
        return Role::query()
            ->withoutGlobalScopes()
            ->whereHas('organizations', function (Builder $query) use ($ids) {
                $query->whereIn('organization_id', $ids);
            })->with('organizations:id')
            ->select('id', 'name')
            ->get();
    }
}
