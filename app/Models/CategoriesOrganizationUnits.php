<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriesOrganizationUnits extends Model
{
   use HasFactory;

   public $timestamps = false;

   protected $fillable = [
      'id',
      'organization_id',
      'category_id',
   ];
}