<?php

namespace App\Collections\Roles;

use App\Collections\BaseCollections\ResourceCollection;
use App\Resources\Roles\RoleListResource;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;

class RoleListCollection extends ResourceCollection
{
    public $collects = RoleListResource::class;

    public function toArray($request): array|Collection|\JsonSerializable|Arrayable
    {
        return $this->collection;
    }
}
