<?php

namespace App\Services\Permissions;

use App\Models\Permission;
use App\Models\PermissionOrganization;

class StoreChildPermissionService
{
   /**
    * Run the store child permission service
    *
    * @param array $requestData
    * @return void
    */
   public function run(array $requestData): void
   {
       $permission = Permission::create($requestData);

       if (session()->has('organization_id')) {
           PermissionOrganization::query()->updateOrInsert(['permission_id' => $permission->id], [
               'permission_id' => $permission->id,
               'organization_id' => session()->get('organization_id')
           ]);
       }
   }
}
