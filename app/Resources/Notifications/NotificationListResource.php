<?php

namespace App\Resources\Notifications;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class NotificationListResource extends JsonResource
{
    /**
     * Return the model attribute
     *
     * @param $request
     * @return array|Collection|\JsonSerializable|Arrayable
     */
    public function toArray($request): array|Collection|\JsonSerializable|Arrayable
    {
        return [
            'title' => app()->getLocale() === 'vn' ? $this->resource->title : $this->resource->title_en,
            'content' => app()->getLocale() === 'vn' ? $this->resource->content : $this->resource->content_en,
            'status' => $this->resource->status,
            'created_at' => $this->resource->created_at->diffForHumans(),
        ];
    }
}
