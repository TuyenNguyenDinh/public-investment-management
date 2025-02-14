<?php

namespace App\Http\Requests\Users;

use App\Http\Requests\BaseRequests\ApiRequest;
use App\Rules\CheckRoleHasOrganizationRule;
use Illuminate\Contracts\Validation\ValidationRule;

class StoreUserRequest extends ApiRequest
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
            'name' => 'required|string|max:50',
            'email' => 'required|string|email|max:50|unique:users',
            'role' => 'required|array',
            'organizations' => 'required|array',
            'password' => 'required|string|min:6',
            'menus' => 'nullable|array',
            'confirm_password' => 'required|string|min:6|same:password',
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
            'name' => __('name'),
            'email' => __('email'),
            'role' => __('role'),
            'organizations' => __('organizations'),
            'password' => __('password'),
            'confirm_password' => __('confirm_password'),
            'menus' => __('Menus'),
        ];
    }
}
