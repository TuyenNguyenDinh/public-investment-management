<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait ApiResponse
{
    public function responseSuccess(?string $message = 'Success', ?int $code = Response::HTTP_OK): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'code' => $code,
        ], $code);
    }

    public function responseSuccessWithData(mixed $data, ?int $code = Response::HTTP_OK): JsonResponse
    {
        return response()->json([
            'message' => 'Success',
            'data' => $data,
            'code' => $code,
        ], $code);
    }

    public function responseError(mixed $message, ?int $code = Response::HTTP_BAD_REQUEST): JsonResponse
    {
        return response()->json([
            'error' => $message,
            'code' => $code
        ], $code);
    }

    public function formatJson(string $class, mixed $attribute)
    {
        return new $class($attribute);
    }
}
