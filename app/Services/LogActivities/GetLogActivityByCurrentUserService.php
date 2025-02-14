<?php

namespace App\Services\LogActivities;

use App\Models\LogActivity;
use Illuminate\Database\Eloquent\Collection;

class GetLogActivityByCurrentUserService
{
    /**
     * Run the get log activities for current user 
     * 
     * @return Collection
     */
    public function run(): Collection
    {
        $userId = auth()->id();

        return LogActivity::query()->where('user_id', $userId)
            ->orderByDesc('created_at')
            ->get();
    }
}
