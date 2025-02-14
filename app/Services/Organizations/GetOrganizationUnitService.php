<?php

namespace App\Services\Organizations;

use App\Models\OrganizationUnit;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class GetOrganizationUnitService
{
    /**
     * Run the get organization unit tree
     *
     * @return Collection
     */
    public function run(): Collection
    {
    
        return OrganizationUnit::query()
            ->when(session()->has('organization_id'), function (Builder $q) {
                $q->where('id', session('organization_id'))
                    ->orWhereDescendantOf(session('organization_id'));
            })
            ->get()->toTree();
    }
}
