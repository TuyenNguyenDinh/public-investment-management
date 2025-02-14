<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'start_date', 'end_date', 'organization_name', 'organization_phone_number'
    ];

    public function startDate(): Attribute
    {
        $dateFormat = app()->getLocale() === 'vn' ? 'd-m-Y' : 'Y-m-d';

        return Attribute::make(
            get: fn ($value) => date($dateFormat, strtotime($value)),
        );
    }

    public function endDate(): Attribute
    {
        $dateFormat = app()->getLocale() === 'vn' ? 'd-m-Y' : 'Y-m-d';

        return Attribute::make(
            get: fn ($value) => date($dateFormat, strtotime($value)),
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
}
