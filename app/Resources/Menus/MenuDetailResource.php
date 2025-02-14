<?php

namespace App\Resources\Menus;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;

class MenuDetailResource extends JsonResource
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
