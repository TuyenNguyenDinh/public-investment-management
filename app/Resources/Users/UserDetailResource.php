<?php

namespace App\Resources\Users;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class UserDetailResource extends JsonResource
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
            'email' => $this->email,
            'role' => $this->getRoleNames(),
            'organizations' => $this->organizations->pluck('id')->toArray(),
            'menus' => $this->menus->pluck('id')->toArray(),
            'created_at' => $this->created_at,
        ];
    }
}
