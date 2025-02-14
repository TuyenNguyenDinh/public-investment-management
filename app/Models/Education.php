<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Education extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'school_name', 'education_level', 'major', 'education_type',
        'rank_level', 'graduation_date', 'certificate_image'
    ];
    
    public function graduationDate(): Attribute
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
