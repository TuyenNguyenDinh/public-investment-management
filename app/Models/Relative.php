<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Relative extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'name', 'relationship', 'address', 'phone_number'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
}
