<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostApprovalLog extends Model
{
    protected $fillable = [
        'post_id',
        'user_id',
        'action',
        'note',
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
