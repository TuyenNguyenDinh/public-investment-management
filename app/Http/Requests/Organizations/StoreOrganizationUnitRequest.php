<?php

namespace App\Http\Requests\Organizations;

use App\Http\Requests\BaseRequests\ApiRequest;
use Illuminate\Contracts\Validation\ValidationRule;

class StoreOrganizationUnitRequest extends ApiRequest
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
            'name' => 'required|string|unique:organization_units,name|max:100',
            'description' => 'bail|nullable|string|max:1000',
            'parent_id' => 'bail|nullable|exists:organization_units,id',
            'phone_number' => 'bail|required|string|max:15|min:10|regex:/^([0-9\s\-\+\(\)]*)$/|unique:organization_units,phone_number',
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
