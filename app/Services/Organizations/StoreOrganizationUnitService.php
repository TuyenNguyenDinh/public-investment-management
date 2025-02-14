<?php

namespace App\Services\Organizations;

use App\Models\OrganizationUnit;

class StoreOrganizationUnitService
{
    /**
     * Run the store organization unit tree
     *
     * @param array $requestData
     * @return void
     */
    public function run(array $requestData): void
    {
        /** @var OrganizationUnit $unit */
        $parentId = $requestData['parent_id'] ?? null;
        $requestData['user_created_id'] = auth()->id();
        $unit = OrganizationUnit::create($requestData);
        if ($parentId) {
            $unit->appendToNode(OrganizationUnit::find($parentId))->save();
        }
    }
}
