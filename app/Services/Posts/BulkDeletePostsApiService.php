<?php

namespace App\Services\Posts;

use App\Models\Post;

class BulkDeletePostsApiService
{
    /**
     * Bulk delete posts
     *
     * @param array $ids
     * @return mixed
     */
    public function run(array $ids): mixed
    {
        return Post::whereIn('id', $ids)->delete();
    }
}
