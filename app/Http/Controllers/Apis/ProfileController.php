<?php

namespace App\Http\Controllers\Apis;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Profiles\UpdateProfileRequest;
use App\Services\Profiles\DeleteEducationProfileService;
use App\Services\Profiles\UpdateProfileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfileController extends ApiController
{
    /**
     * Update profile for current user
     * 
     * @param UpdateProfileRequest $request
     * @return JsonResponse
     */
    public function update(UpdateProfileRequest $request): JsonResponse
    {
        $request = $request->validated();
        resolve(UpdateProfileService::class)->run($request);
    
        return $this->responseSuccess();
    }

    /**
     * Delete education profile for current user
     * 
     * @param int $id
     * @return JsonResponse
     */
    public function deleteEducation(int $id): JsonResponse
    {
        resolve(DeleteEducationProfileService::class)->run($id);
        
        return $this->responseSuccess();
    }
}
