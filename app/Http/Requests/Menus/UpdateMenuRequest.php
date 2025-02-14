<?php

namespace App\Http\Requests\Menus;

use App\Http\Requests\BaseRequests\ApiRequest;
use App\Rules\CheckOrganizationParentIdSameOrganizationIdRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class UpdateMenuRequest extends ApiRequest
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
        $groupFlag = request()->input('group_menu_flag');
        $appendRule = [];
        $rules = [
            'name' => ['required', 'string', 'max:50', Rule::unique('menus')->ignore($this->id)],
            'group_menu_flag' => 'boolean',
        ];

        if (!$groupFlag) {
            $appendRule = [
                'parent_id' => ['nullable', 'exists:menus,id', new CheckOrganizationParentIdSameOrganizationIdRule($this->id)],
                'icon' => 'required|string|max:50',
                'slug' => 'required|string|max:100',
                'url' => 'required|string|max:100',
            ];
        }
        
        return array_merge($rules, $appendRule);
    }
}
