<?php

namespace App\Services\Users;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UpdateUserByIdService
{
    /**
     * Run the update user by id service
     *
     * @param int $id
     * @param array<string, mixed> $requestData
     * @return void
     * @throws Exception
     */
    public function run(int $id, array $requestData): void
    {
        DB::beginTransaction();
        try {
            $user = User::query()->findOrFail($id);
            if (!empty($requestData['password'])) {
                $requestData['password'] = Hash::make($requestData['password']);
            } else {
                unset($requestData['password']);
            }
            $user->update($requestData);
            if (!empty($requestData['role'])) {
                $user->syncRoles($requestData['role']);
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
