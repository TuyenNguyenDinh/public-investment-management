<?php

namespace App\Resources\Organizations;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class OrganizationUnitListResource extends JsonResource
{
   /**
    * Return the model attribute
    *
    * @param $request
    * @return array|Collection|\JsonSerializable|Arrayable
    */
   public function toArray($request): array|Collection|\JsonSerializable|Arrayable
   {
      return $this->resource;
   }
}
