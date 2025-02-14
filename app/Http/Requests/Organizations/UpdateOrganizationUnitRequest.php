<?php

namespace App\Http\Requests\Organizations;

use App\Http\Requests\BaseRequests\ApiRequest;
use App\Rules\CheckOrganizationParentIdSameOrganizationIdRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class UpdateOrganizationUnitRequest extends ApiRequest
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
            'name' => ['required', 'string', 'max:255', Rule::unique('organization_units')->ignore($this->id)],
            'description' => 'nullable|string',
            'parent_id' => ['nullable', 'exists:organization_units,id', new CheckOrganizationParentIdSameOrganizationIdRule($this->id)],
            'phone_number' => ['bail', 'required', 'max:15', 'min:10', 'regex:/^([0-9\s\-\+\(\)]*)$/',
                Rule::unique('organization_units')->ignore($this->id)],
            'address' => 'bail|nullable|string|max:95',
            'tax_code' => 'bail|nullable|string|max:255',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => __('organization_name'),
            'description' => __('organization_description'),
            'parent_id' => __('parent_organization'),
            'phone_number' => __('phone_number'),
            'address' => __('organization_address'),
            'tax_code' => __('tax_code'),
        ];
    }
}
