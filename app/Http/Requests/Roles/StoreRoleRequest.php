<?php

namespace App\Http\Requests\Roles;

use App\Http\Requests\BaseRequests\ApiRequest;
use Illuminate\Contracts\Validation\ValidationRule;

class StoreRoleRequest extends ApiRequest
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
            'name' => 'required|string|max:50|unique:roles,name',
            'permissions' => 'array',
            'organizations' => 'required',
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
            'name' => __('role_name'),
            'permissions' => __('permissions'),
            'organizations' => __('organizations'),
        ];
    }
}
