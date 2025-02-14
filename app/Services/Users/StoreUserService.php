<?php

namespace App\Services\Users;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StoreUserService
{
    /**
     * Run the store user service
     *
     * @param array $requestData
     * @return void
     * @throws Exception
     */
    public function run(array $requestData): void
    {
        array_pop($requestData);
        DB::beginTransaction();
        try {
            $user = User::create($requestData);
            if (!empty($requestData['role'])) {
                $user->assignRole($requestData['role']);
            }

            if (!empty($requestData['organizations'])) {
                $user->organizations()->sync($requestData['organizations']);
            }
            if (!empty($requestData['menus'])) {
                $user->menus()->sync($requestData['menus']);
            }

            DB::commit();
        } catch (Exception $e) {
            Log::debug($e);
            DB::rollBack();
            throw $e;
        }
    }
}
