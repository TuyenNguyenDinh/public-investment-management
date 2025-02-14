<?php

namespace App\Services\Users;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class GetUserByIdService
{
   /**
    * Run the find user by id service
    * 
    * @param int $id
    * @return User|Collection|Model|null
    */
   public function run(int $id): Model|Collection|User|null
   {
      return User::query()->with(['roles', 'organizations', 'menus'])->findOrFail($id);
   }
}
