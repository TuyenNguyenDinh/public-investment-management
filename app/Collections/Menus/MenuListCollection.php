<?php

namespace App\Collections\Menus;

use App\Collections\BaseCollections\ResourceCollection;
use App\Resources\Menus\MenuListResource;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;

class MenuListCollection extends ResourceCollection
{
   public $collects = MenuListResource::class;

   public function toArray($request): array|Collection|\JsonSerializable|Arrayable
   {
      return $this->collection;
   }
}
