<?php

namespace App\Services\Roles;


use App\Models\Role;

class DeleteRoleByIdService
{
   /**
    * Run the delete role by id service
    *
    * @param int $id
    * @return void
    */
   public function run(int $id): void
   {
      $role = Role::query()->find($id);
      $role->permissions()->detach();
      $role->delete();
   }
}
