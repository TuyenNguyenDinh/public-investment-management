<?php

namespace App\Services\Permissions;


use App\Models\Permission;

class DeletePermissionByIdService
{
   /**
    * Run the delete permission by id service
    *
    * @param int $id
    * @return void
    */
   public function run(int $id): void
   {
      $permission = Permission::query()->where('id', $id)->first();
      Permission::query()->where('parent_id', $permission->id)->delete();
      $permission->delete();
   }
}
