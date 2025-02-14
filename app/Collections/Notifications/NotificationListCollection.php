<?php

namespace App\Collections\Notifications;

use App\Collections\BaseCollections\ResourceCollection;
use App\Resources\Notifications\NotificationListResource;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;

class NotificationListCollection extends ResourceCollection
{
    public $collects = NotificationListResource::class;

    public function toArray($request): array|Collection|\JsonSerializable|Arrayable
    {
        return $this->collection;
    }
}
