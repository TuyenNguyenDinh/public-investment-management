<?php

namespace App\Services\Logs;

use App\Models\Account;
use App\Models\LogActivity;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class GetLogByCurrentOrganizationService
{
    /**
     * Run the get log activities for current user 
     * 
     * @return Collection
     */
    public function run(): Collection
    {
        $userOgrsId = Account::query()->pluck('id')->toArray();

        return LogActivity::query()->whereIn('user_id', $userOgrsId)
            ->with(['user:id,name', 'organization:id,name'])
            ->orderByDesc('created_at')
            ->get();
    }
}
