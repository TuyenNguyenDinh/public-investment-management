<?php

namespace App\Http\Requests\BaseRequests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ApiRequest extends FormRequest
{
   /**
    * Return failed validation with json
    *
    * @param Validator $validator
    * @return JsonResponse
    */
   protected function failedValidation(Validator $validator): JsonResponse
   {
      throw new HttpResponseException(response()->json([
         'success' => false,
         'message' => 'Validation errors',
         'errors' => $validator->errors()
      ], Response::HTTP_UNPROCESSABLE_ENTITY));
   }
}
