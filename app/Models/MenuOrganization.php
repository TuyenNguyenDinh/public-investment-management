<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuOrganization extends Model
{
    use HasFactory;
    
    protected $table = 'menus_organizations';
    
    protected $fillable = [
        'menu_id',
        'organization_id',
    ];
}
