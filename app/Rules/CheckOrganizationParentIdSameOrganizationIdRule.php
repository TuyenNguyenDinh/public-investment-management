<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CheckOrganizationParentIdSameOrganizationIdRule implements ValidationRule
{
    protected int $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
       if ($value == $this->id) {
           $fail(__('organization_parent_id_same_organization_id'));
       }
    }
}
