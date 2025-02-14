<?php

namespace Database\Seeders;

use App\Enums\Posts\PostType;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AddPostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Post::query()->truncate();
        $post = Post::create([
            'title' => 'Post 1',
            'slug' => Str::slug('Post 1') . '-' . \str()->random(10),
            'created_by' => 1,
            'updated_by' => 1,
            'thumbnail' => 'https://via.placeholder.com/150',
            'content' => 'Content of post 1',
            'scheduled_date' => Carbon::now()->addDays(30),
            'status' => PostType::DRAFT,
        ]);
        $post->categories()->sync([
            'post_id' => $post->id,
            'category_id' => 1,
        ]);
        $post->organizations()->sync([
            'post_id' => $post->id,
            'organization_id' => 1,
        ]);

    }
}
