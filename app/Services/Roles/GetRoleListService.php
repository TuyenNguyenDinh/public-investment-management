<?php

namespace App\Services\Roles;

use App\Models\Role;
use Illuminate\Database\Eloquent\Collection;

class GetRoleListService
{
    /**
     * Run the get role service
     *
     * @return Collection
     */
    public function run(): Collection
    {
        return Role::query()->select('id', 'name', 'created_at')
            ->with('permissions:id,name')->get();
    }
}
