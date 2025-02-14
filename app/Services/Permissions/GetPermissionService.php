<?php

namespace App\Services\Permissions;

use App\Models\Permission;
use Illuminate\Database\Eloquent\Collection;

class GetPermissionService
{
    /**
     * Run the get permission list
     * 
     * @return Collection
     */
    public function run(): Collection
    {
        return Permission::query()->select('id', 'name', 'parent_id', 'created_at')
        ->orderByRaw('IF(parent_id IS NULL, id, parent_id), parent_id IS NOT NULL')
        ->get();
    }
}
