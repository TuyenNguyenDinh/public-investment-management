<?php

namespace App\Collections\Users;

use App\Collections\BaseCollections\ResourceCollection;
use App\Resources\Users\UserListResource;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;

class UserListCollection extends ResourceCollection
{
    public $collects = UserListResource::class;

    public function toArray($request): array|Collection|\JsonSerializable|Arrayable
    {
        return $this->collection;
    }
}
