<?php

namespace App\Collections\Logs;

use App\Collections\BaseCollections\ResourceCollection;
use App\Resources\Logs\LogListResource;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Collection;

class LogListCollection extends ResourceCollection
{
    public $collects = LogListResource::class;

    public function toArray($request): array|Collection|\JsonSerializable|Arrayable
    {
        return $this->collection;
    }
}
