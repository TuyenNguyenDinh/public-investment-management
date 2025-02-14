<?php

namespace App\Collections\BaseCollections;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection as BaseResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class ResourceCollection extends BaseResourceCollection
{
    /**
     * Make the json response
     * 
     * @param $request
     * @return JsonResponse
     */
    public function toResponse($request): JsonResponse
    {
        return JsonResource::toResponse($request);
    }

    /**
     * Format the json response
     * 
     * @param $request
     * @return array
     */
    public function with($request): array
    {
        return [
            'message' => 'Success',
            'status' => Response::HTTP_OK,
        ];
    }
}
