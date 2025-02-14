<?php

namespace App\Services\Permissions;

use App\Models\PermissionOrganization;
use Spatie\Permission\Models\Permission;

class StorePermissionService
{
    /**
     * Run the store permission service
     * 
     * @param array $requestData
     * @return void
     */
    public function run(array $requestData): void
    {
       $permission = Permission::create(['name' => $requestData['modalPermissionName']]);

        if (session()->has('organization_id')) {
            PermissionOrganization::query()->updateOrInsert(['permission_id' => $permission->id], [
                'permission_id' => $permission->id,
                'organization_id' => session()->get('organization_id')
            ]);
        }
    }
}
