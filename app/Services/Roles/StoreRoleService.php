<?php

namespace App\Services\Roles;

use App\Models\Role;
use App\Models\RoleOrganization;

class StoreRoleService
{
    /**
     * Run the store role service
     *
     * @param array $requestData
     * @return void
     */
    public function run(array $requestData): void
    {
        $role = Role::create(['name' => $requestData['name']]);

        if (empty($requestData['organizations'])) {
            return;
        }

        RoleOrganization::query()->insert([
            'role_id' => $role->id,
            'organization_id' => $requestData['organizations'],
        ]);
        $role->syncPermissions($requestData['permissions']);
    }
}
