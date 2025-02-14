<?php

namespace App\Services\Auth;

use App\Models\Account;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class ChooseOrganizationUnitService
{
    /**
     * Get all organizations by user logged
     *
     * @return \Kalnoy\Nestedset\Collection|Collection Collection
     */
    public function run(): \Kalnoy\Nestedset\Collection|Collection
    {
        $organizations = collect();
        if (Auth::check()) {
            $user = Account::query()->find(Auth::user()->id);
            $organizations = $user->getAllOrganizationTrees();
        }

        return $organizations;
    }
}
