<?php

namespace App\Services\Organizations;

use App\Models\OrganizationUnit;

class GetOrganizationUnitByIdService
{
   /**
    * Run the get organization unit tree by id
    *
    * @param int $id
    * @return OrganizationUnit
    */
   public function run(int $id): OrganizationUnit
   {
      return OrganizationUnit::query()->with('parent:id')->find($id);
   }
}
