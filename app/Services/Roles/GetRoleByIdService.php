<?php

namespace App\Services\Roles;

use App\Models\Role;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class GetRoleByIdService
{
    /**
     * Run the find role by id service
     *
     * @param int $id
     * @return Collection|Model|Role|null
     */
    public function run(int $id): Model|Collection|Role|null
    {
        return Role::query()->with(['permissions', 'organizations'])->findOrFail($id);
    }
}
