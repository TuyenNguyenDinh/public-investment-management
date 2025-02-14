<?php

namespace App\Services\Profiles;

use App\Models\Education;

class DeleteEducationProfileService
{
    /**
     * Run the delete education profile for current user
     *
     * @param int $id
     * @return void
     */
    public function run(int $id): void
    {
        $userId = auth()->id();
        Education::query()->where([
            'user_id' => $userId,
            'id' => $id
        ])->delete();
    }
}
