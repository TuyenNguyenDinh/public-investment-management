<?php

namespace App\Services\Roles;

use App\Models\Account;
use Illuminate\Database\Eloquent\Builder;

class CountUserByConditionService
{
    /**
     * Run the count user by custom condition
     * 
     * @param array|null $condition
     * @return Builder
     */
    public function run(?array $condition = []): Builder
    {
        return Account::query()
        ->when(!empty($condition), function (Builder $q) use ($condition) {
            $q->where($condition);
        });
    }
}
