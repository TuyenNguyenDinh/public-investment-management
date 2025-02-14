<?php

namespace App\Resources\Posts;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class PostDetailResource extends JsonResource
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
            'id' => $this->id,
            'categories' => $this->categories,
            'organizations' => $this->organizations,
            'content' => $this->content,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'scheduled_date' => $this->scheduled_date,
            'slug' => $this->slug,
            'thumbnail' => $this->thumbnail ?? Storage::disk('public')->url('/images/thumbnail-image.jpg'),
            'title' => $this->title,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
            'views' => $this->views,
            'isViewed' => count($this->logsUsersViewed) > 0,
            'status' => $this->status,
            'creator' => $this->creator->name,
            'updater' => $this->updater->name,
        ];
    }
}
