<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostsOrganizations extends Model
{
   use HasFactory;

   public $timestamps = false;

   protected $fillable = [
      'id',
      'post_id',
      'organization_id',
   ];
}