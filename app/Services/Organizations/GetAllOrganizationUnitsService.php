<?php

namespace App\Services\Organizations;

use App\Models\OrganizationUnit;
use Illuminate\Database\Eloquent\Collection;

class GetAllOrganizationUnitsService
{
    /**
     * Run the get organization unit tree
     *
     * @return Collection
     */
    public function run(): Collection
    {
       return OrganizationUnit::query()        
            ->descendantsAndSelf(session('organization_id'))->toTree();
    }
}
