<?php

namespace App\Collections\LogActivities;

use App\Collections\BaseCollections\ResourceCollection;
use App\Resources\LogActivities\LogActivityListResource;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Collection;

class LogActivityListCollection extends ResourceCollection
{
    public $collects = LogActivityListResource::class;

    public function toArray($request): array|Collection|\JsonSerializable|Arrayable
    {
        return $this->collection;
    }
}
