<?php

namespace App\Models;

use App\Traits\SetDateTimeConfigTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Application;

class LogActivity extends Model
{
    use HasFactory;
    use SoftDeletes;
    use SetDateTimeConfigTrait;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table;

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
        'description',
        'user_type',
        'user_id',
        'organization_id',
        'route',
        'ip_address',
        'user_agent',
        'locale',
        'country',
        'method_type',
    ];

    /**
     * An activity has a user.
     *
     * @return HasOne
     */
    public function user(): HasOne
    {
        return $this->hasOne(Account::class, 'id', 'user_id');
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(OrganizationUnit::class, 'organization_id');
    }

    /**
     * Get a validator for an incoming Request.
     *
     * @param array $merge (rules to optionally merge)
     *
     * @return array
     */
    public static function rules(array $merge = []): array
    {
        if (app() instanceof Application) {
            $route_url_check = version_compare(Application::VERSION, '5.8') < 0 ? 'active_url' : 'url';
        } else {
            $route_url_check = 'url';
        }

        return array_merge(
            [
                'description'   => 'required|string',
                'user_type'      => 'required|string',
                'user_id'        => 'nullable|integer',
                'route'         => 'nullable|'.$route_url_check,
                'ip_address'     => 'nullable|ip',
                'user_agent'     => 'nullable|string',
                'locale'        => 'nullable|string',
                'country'       => 'nullable|string',
                'method_type'    => 'nullable|string',
            ],
            $merge
        );
    }
}
