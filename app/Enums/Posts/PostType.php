<?php

namespace App\Enums\Posts;

enum PostType: int
{
    const DRAFT = 0;
    const APPROVED = 1;
    const SCHEDULED = 2;
    const SUBMITTED = 3;
    const REJECTED = 4;
    const LOCKED = 5;

    /**
     * Return an array of translations for each post type.
     *
     * @return array<string, string>
     */
    public static function postTypeText(): array
    {
        return [
            self::DRAFT => __('posts_draft'),
            self::APPROVED => __('posts_approved'),
            self::SCHEDULED => __('posts_scheduled'),
            self::SUBMITTED => __('posts_submitted'),
            self::REJECTED => __('posts_rejected'),
            self::LOCKED => __('posts_locked'),
        ];
    }

    /**
     * Return an array of the post types.
     *
     * @return int[]
     */
    public static function postTypeArray(): array
    {
        return [
            self::DRAFT,
            self::APPROVED,
            self::SCHEDULED,
            self::SUBMITTED,
            self::REJECTED,
            self::LOCKED,
        ];
    }
}
