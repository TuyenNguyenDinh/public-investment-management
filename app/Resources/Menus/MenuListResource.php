<?php

namespace App\Resources\Menus;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class MenuListResource extends JsonResource
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
