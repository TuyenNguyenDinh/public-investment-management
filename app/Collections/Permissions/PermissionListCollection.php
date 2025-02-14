<?php

namespace App\Collections\Permissions;

use App\Collections\BaseCollections\ResourceCollection;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
use App\Resources\Permissions\PermissionListResource;

class PermissionListCollection extends ResourceCollection
{
    public $collects = PermissionListResource::class;

    public function toArray($request): array|Collection|\JsonSerializable|Arrayable
    {
        return $this->collection;
    }
}
