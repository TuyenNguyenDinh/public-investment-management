<?php

namespace App\Resources\Roles;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class RoleDetailResource extends JsonResource
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
            'id' => $this->id,
            'name' => $this->name,
            'created_at' => $this->created_at,
            'permissions' => $this?->permissions,
            'organizations' => $this?->organizations->pluck('id')->toArray(),
        ];
    }
}
