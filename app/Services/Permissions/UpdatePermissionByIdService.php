<?php

namespace App\Services\Permissions;

use App\Models\PermissionOrganization;
use Spatie\Permission\Models\Permission;

class UpdatePermissionByIdService
{
    /**
     * Run the update permission by id service
     *
     * @param int $id
     * @param array $requestData
     * @return void
     */
    public function run(int $id, array $requestData): void
    {
        Permission::query()->where('id', $id)
            ->update(['name' => $requestData['editPermissionName']]);

        if (session()->has('organization_id')) {
            PermissionOrganization::query()->updateOrInsert(['permission_id' => $id], [
                'permission_id' => $id,
                'organization_id' => session()->get('organization_id')
            ]);
        }
    }
}
