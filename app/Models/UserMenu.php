<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserMenu extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'menu_id',
    ];
    
    /**
     * The menu that this user has access to.
     */
    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }
    
    /**
     * The user that has access to this menu.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
