<?php

namespace App\Models;

use App\Models\Scopes\CheckOrganizationScope;
use App\Traits\CommonNestedSetTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Kalnoy\Nestedset\NodeTrait;

class Menu extends Model
{
    use HasFactory;
    use NodeTrait;
    use CommonNestedSetTrait;
 
    protected $fillable = [
      'name',
      'icon',
      'slug',
      'route_name',
      'url',
      'status',
      'allow_delete',
      'group_menu_flag',
      'parent_id'
    ];
    
    protected $appends = ['text'];
}
