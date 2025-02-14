<?php

namespace App\Services\Roles;

use App\Enums\BaseEnum;
use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\DataTables;

class GetRoleListWithDatatableService
{
    /**
     * Run the get role service
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function run(): JsonResponse
    {
        $user = auth()->user();
        $roleList = Role::query()->select('id', 'name', 'created_at')
            ->with('permissions:id,name')->get();

        return DataTables::of($roleList)
            ->editColumn('name', function ($role) {
                return "<span class='text-nowrap text-heading name-$role->id'>$role->name</span>";
            })
            ->addColumn('actions', function ($role) use ($user) {
                $updatePermission = $user->hasOrganizationPermission(BaseEnum::ROLES['UPDATE'], $role->organizations->pluck('id')->toArray());
                $edit = $updatePermission
                    ? "<div class='d-flex align-items-center gap-50'>
                    <button class='btn btn-icon btn-text-secondary waves-effect waves-light rounded-pill me-1 edit-role' data-id='$role->id'>
                        <i class='ti ti-edit ti-md'></i>"
                    : "";
                $delete = $user->hasOrganizationPermission(BaseEnum::ROLES['DELETE'], $role->organizations->pluck('id')->toArray())
                    ? "<button class='btn btn-sm btn-icon delete-record btn-text-secondary rounded-pill waves-effect' data-id='$role->id'>
                        <i class='ti ti-trash'></i>
                    </button>"
                    : "";
                return "<div class='d-flex align-items-center'>
                    $edit $delete
                </div>";
            })
            ->rawColumns(['actions', 'name'])
            ->make();
    }
}
