<?php

namespace App\Collections\Posts;

use App\Resources\Posts\PostDetailResource;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Collection;

class ListPostsCollection extends ResourceCollection
{
    public $collects = PostDetailResource::class;

    public function toArray($request): array|Collection|\JsonSerializable|Arrayable
    {
        return $this->collection;
    }
}