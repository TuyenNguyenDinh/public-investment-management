<?php

namespace App\Services\Permissions;

use App\Models\Permission;
use App\Models\Scopes\CheckOrganizationScope;
use Illuminate\Database\Eloquent\Collection;

class GetPermissionWithChildService
{
   /**
    * Run the get permission list
    *
    * @return Collection
    */
   public function run(): Collection
   {
      return Permission::query()->whereNull('parent_id')
         ->with('childPermission')
         ->select('id', 'name', 'parent_id', 'created_at')
         ->orderByRaw('IF(parent_id IS NULL, id, parent_id), parent_id IS NOT NULL')
         ->withoutGlobalScope(new CheckOrganizationScope())
         ->get();
   }
}
