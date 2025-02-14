<?php

namespace App\Http\Requests\Categories;

use App\Http\Requests\BaseRequests\ApiRequest;
use App\Rules\CheckOrganizationParentIdSameOrganizationIdRule;

class UpdateCategoryRequest extends ApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'organizations' => 'nullable',
            'parent_id' => ['nullable', 'exists:categories,id', new CheckOrganizationParentIdSameOrganizationIdRule($this->id)],
        ];
    }
}
