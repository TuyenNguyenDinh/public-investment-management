<?php

namespace App\Resources\Users;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class UserListResource extends JsonResource
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
            'email' => $this->resource->email,
            'organization_name' => implode(', ', $this->resource->organizations?->pluck('name')?->toArray()),
            'role' => $this->resource->getRoleNames()?->first(),
            'is_active' => $this->resource->is_active,
            'created_at' => $this->resource->created_at,
        ];
    }
}
