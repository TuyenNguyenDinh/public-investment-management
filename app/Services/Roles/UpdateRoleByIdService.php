<?php

namespace App\Services\Roles;

use App\Models\Role;
use App\Models\RoleOrganization;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UpdateRoleByIdService
{
    /**
     * Run the update role by id service
     *
     * @param int $id
     * @param array $requestData
     * @return void
     * @throws \Exception
     */
    public function run(int $id, array $requestData): void
    {
        try {
            DB::beginTransaction();
            $role = Role::findOrFail($id);
            $role->update(['name' => $requestData['name']]);

            $this->updateRoleOrganizations($id, $requestData['organizations']);
            $this->updateRolePermissions($role, $requestData['permissions'] ?? [], $requestData['organizations']);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::debug($e);
            throw $e;
        }
    }

    /**
     * Update role organizations
     *
     * @param int $roleId
     * @param string $organizationId
     * @return void
     */
    private function updateRoleOrganizations(int $roleId, string $organizationId): void
    {
        RoleOrganization::query()->where('role_id', $roleId)->delete();
        RoleOrganization::query()->insert([
            'role_id' => $roleId,
            'organization_id' => $organizationId
        ]);
    }

    /**
     * Update role permissions
     *
     * @param Role $role
     * @param array $permissions
     * @param string $organizationId
     * @return void
     */
    private function updateRolePermissions(Role $role, array $permissions, string $organizationId): void
    {
        if (!empty($permissions)) {
            $role->syncPermissions($permissions);
        }
    }
}
