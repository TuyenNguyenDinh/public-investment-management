<?php

namespace App\Resources\Organizations;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;

class OrganizationUnitDetailResource extends JsonResource
{
   /**
    * Return the model attribute
    *
    * @param $request
    * @return array|Model|\JsonSerializable|Arrayable
    */
   public function toArray($request): array|\JsonSerializable|Arrayable|Model
   {
      return $this->resource;
   }
}
