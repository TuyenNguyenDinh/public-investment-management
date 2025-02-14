<?php

namespace App\Services\Users;

use App\Models\Account;
use Illuminate\Database\Eloquent\Collection;

class GetUserListService
{
    /**
     * Run the get user list service
     *
     * @return Collection
     * @throws \Exception
     */
    public function run(): Collection
    {
        return Account::query()->select(['id', 'name', 'email', 'is_active', 'created_at'])
            ->with(['roles', 'organizations'])
            ->orderBy('id')
            ->get();
    }
}
