<?php

namespace App\Services\Profiles;

use App\Models\User;

class GetProfileByCurrentUserService
{
    /**
     * Run the get current user profile
     * 
     * @return User|null
     */
    public function run(): ?User
   {
        $userId = auth()->id();

        return User::query()->with(['organizations', 'relatives', 'workHistories'])->find($userId);
   }
}
