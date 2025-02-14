<?php

namespace App\Services\Users;

use App\Models\Account;

class TriggerUserByIdService
{
    /**
     * Run the trigger user status
     *
     * @param int $id
     * @param array $data
     * @return void
     */
    public function run(int $id, array $data): void
    {
        Account::query()->find($id)->update($data);
    }
}
