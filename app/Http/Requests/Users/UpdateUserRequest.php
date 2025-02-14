<?php

namespace App\Http\Requests\Users;

use App\Http\Requests\BaseRequests\ApiRequest;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends ApiRequest
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
            'email' => ['required', 'string', 'email', 'max:50', Rule::unique('users', 'email')->ignore($this->id)],
            'role' => 'required|array',
            'organizations' => 'required|array',
            'menus' => 'nullable|array',
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
            'menus' => __('Menus'),
        ];
    }
}
