<?php

namespace App\Resources\Logs;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class LogListResource extends JsonResource
{
    /**
     * Return the model attribute
     *
     * @param $request
     * @return array|Collection|\JsonSerializable|Arrayable
     */
    public function toArray($request): array|Collection|\JsonSerializable|Arrayable
    {
        $translations = [
            'Accessed' => __('accessed'),
            'Created' => __('created'),
            'Edited' => __('edited'),
            'Deleted' => __('deleted'),
            'Logged out' => __('logged_out'),
        ];

        $description = $this->resource->description;
        foreach ($translations as $key => $translation) {
            $description = preg_replace('/\b' . preg_quote($key, '/') . '\b/', $translation, $description);
        }

        return [
            'id' => $this->resource->id,
            'description' => $description,
            'user_type' => $this->resource->user_type,
            'user_id' => $this->resource->user_id,
            'user_name' => $this->resource->user?->name,
            'route' => $this->resource->route,
            'ip_address' => $this->resource->ip_address,
            'user_agent' => $this->resource->user_agent,
            'locale' => $this->resource->locale,
            'country' => $this->resource->country,
            'method_type' => $this->resource->method_type,
            'created_at' => $this->resource->created_at,
            'organization_name' => $this->resource?->organization?->name,
        ];
    }
}
