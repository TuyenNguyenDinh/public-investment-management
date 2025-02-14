<?php

namespace App\Models;

use App\Traits\SetDateTimeConfigTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LogUserViewedPost extends Model
{
    use HasFactory;
    use SoftDeletes;
    use SetDateTimeConfigTrait;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'log_user_viewed_post';

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id',
    ];

    /**
     * Fillable fields for a Profile.
     *
     * @var array
     */
    protected $fillable = [
        'user_type',
        'user_id',
        'post_id',
        'ip_address',
        'user_agent',
        'locale',
        'country',
    ];
}
