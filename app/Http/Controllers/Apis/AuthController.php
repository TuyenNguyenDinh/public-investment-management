<?php

namespace App\Http\Controllers\Apis;

use App\Http\Controllers\ApiController;
use App\Http\Requests\ChooseOrganizationAuthRequest;
use Illuminate\Http\JsonResponse;

class AuthController extends ApiController
{
   /**
    * Choose organization authentication
    *
    * @param ChooseOrganizationAuthRequest $request
    * @return JsonResponse
    */
   public function chooseOrganization(ChooseOrganizationAuthRequest $request): JsonResponse
   {
      session()->put('organization_id', $request->organization);
      return $this->responseSuccess();
   }
}
