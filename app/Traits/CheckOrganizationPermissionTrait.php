<?php

namespace App\Traits;

use App\Models\OrganizationUnit;
use Illuminate\Support\Facades\Auth;

trait CheckOrganizationPermissionTrait
{

    /**
     * @param string $permission
     * @param $organizationId
     * @return bool
     */
    public function hasOrganizationPermission(string $permission, $organizationId): bool
    {
        // Check to see if the logged in organization is assigned to the user.
        // This case occurs when the currently logged-in user has their permissions updated by the admin.
        $requestOrganization = OrganizationUnit::query()->find(session('organization_id'));
        $assignedOrganizations = $this->getAllAssignedOrganizationIds();
        if (!in_array($requestOrganization->id, $assignedOrganizations)) {
            return false;
        }

        // 1. Lấy danh sách các tổ chức cha và chính tổ chức cần kiểm tra quyền, bỏ qua global scope
        $organizationAncestors = [];
        if (is_array($organizationId)) {
            foreach ($organizationId as $id) {
                $orgs = OrganizationUnit::withoutGlobalScopes()
                    ->ancestorsAndSelf($id)
                    ->pluck('id')
                    ->toArray();
                $organizationAncestors = array_merge($organizationAncestors, $orgs);
            }
        } else {
            $organizationAncestors = OrganizationUnit::withoutGlobalScopes()
                ->ancestorsAndSelf($organizationId)
                ->pluck('id')
                ->toArray();
        }
        // 2. Truy vấn các vai trò của người dùng có liên kết với các tổ chức trong nhánh này, bỏ qua global scope
        $userRoles = Auth::guard('web')->user()->roles()
            ->withoutGlobalScopes()
            ->whereHas('organizations', function ($query) use ($organizationAncestors) {
                $query->whereIn('organization_id', $organizationAncestors);
            })
            ->get();
        // 3. Kiểm tra quyền trên từng vai trò của người dùng
        foreach ($userRoles as $role) {
            if ($role->checkPermissionTo($permission)) {
                return true; // Người dùng có quyền cần thiết trong một trong các vai trò
            }
        }

        return false; // Người dùng không có quyền cần thiết
    }

    /**
     * @return mixed
     */
    public function getAllOrganizationTrees(): mixed
    {
        // Lấy danh sách ID của tất cả các tổ chức mà user này thuộc về
        $organizationIds = $this->organizations()->withoutGlobalScopes()->pluck('organization_units.id');

        // Lấy chính nó và tất cả con cháu của từng tổ chức trong danh sách
        return OrganizationUnit::query()->withoutGlobalScopes()
            ->whereIn('id', $organizationIds)
            ->orWhereHas('ancestors', function ($query) use ($organizationIds) {
                $query->whereIn('id', $organizationIds);
            })
            ->get()
            ->toTree();
    }

    /**
     * Get all assigned organization IDs.
     *
     * @return array
     */
    public function getAllAssignedOrganizationIds(): array
    {
        $ids = [];
        $assignedOrganizations = $this->getAllOrganizationTrees();
        foreach ($assignedOrganizations as $assignedOrganization) {
            // pluck grand children ids
            $grandIds = [];
            foreach ($assignedOrganization->children as $child) {
                $grandIds = array_merge($grandIds, $child?->children->pluck('id')->toArray() ?? []);
            }

            $ids = array_merge($ids, [
                $assignedOrganization->id,
                ...$assignedOrganization?->children->pluck('id')->toArray() ?? [],
                ...$grandIds
            ]);
        }

        return $ids;
    }
}
