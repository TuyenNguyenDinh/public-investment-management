<?php

namespace App\Services\Posts;

use App\Models\Post;

class GetPostDetailService
{
    public function run(string $slug)
    {
        return Post::with('categories', 'organizations')->where('slug', $slug)->firstOrFail();
    }
}