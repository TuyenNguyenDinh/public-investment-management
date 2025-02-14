<?php

namespace App\Services\Organizations;

use App\Models\OrganizationUnit;

class DeleteOrganizationUnitByIdService
{
   /**
    * Run the delete organization unit tree by id
    *
    * @param int $id
    * @return void
    */
   public function run(int $id): void
   {
      OrganizationUnit::query()->find($id)->delete();
   }
}
