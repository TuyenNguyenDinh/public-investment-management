<?php

namespace App\Services\Users;

use App\Models\Account;

class DeleteUserByIdService
{
   /**
    * Run the delete user by id service
    *
    * @param int $id
    * @return void
    */
    public function run(int $id): void
    {
        Account::query()->where('id', $id)->delete();
    }
}
