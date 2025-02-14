<?php

namespace App\Resources\Permissions;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class PermissionListResource extends JsonResource
{
    /**
     * Return the model attribute
     * 
     * @param $request
     * @return array|Collection|\JsonSerializable|Arrayable
     */
    public function toArray($request): array|Collection|\JsonSerializable|Arrayable
    {
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'parent_id' => $this->resource->parent_id,
            'created_at' => $this->resource->created_at,
        ];
    }
}
