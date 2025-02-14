<?php

namespace App\Collections\OrganizationUnits;

use App\Collections\BaseCollections\ResourceCollection;
use App\Resources\Organizations\OrganizationUnitListResource;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;

class OrganizationUnitListCollection extends ResourceCollection
{
   public $collects = OrganizationUnitListResource::class;

   public function toArray($request): array|Collection|\JsonSerializable|Arrayable
   {
      return $this->collection;
   }
}
