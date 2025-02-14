<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;

trait CommonNestedSetTrait
{
   public function text(): Attribute
   {
      return new Attribute(get: fn() => $this->name);
   }
}
