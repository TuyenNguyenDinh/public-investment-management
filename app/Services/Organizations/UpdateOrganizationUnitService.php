<?php

namespace App\Services\Organizations;

use App\Models\OrganizationUnit;
use Kalnoy\Nestedset\Collection;

class UpdateOrganizationUnitService
{
    /**
     * Run the update organization unit tree by id
     *
     * @param int $id
     * @param array $requestData
     * @return void
     */
    public function run(int $id, array $requestData): void
    {
        $organization = OrganizationUnit::query()->find($id);
        $organization->update($requestData);
        $organization->makeRoot()->save();
        if (!empty($requestData['parent_id']) && $organization->parent_id != $requestData['parent_id']) {
            $organization->appendToNode(OrganizationUnit::query()->find($requestData['parent_id']))->save();
        }
    }
}
