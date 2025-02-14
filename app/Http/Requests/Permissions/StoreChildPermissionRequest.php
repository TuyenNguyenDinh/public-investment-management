<?php

namespace App\Http\Requests\Permissions;

use App\Http\Requests\BaseRequests\ApiRequest;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class StoreChildPermissionRequest extends ApiRequest
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
         'parent_id' => 'required|numeric|exists:permissions,id',
         'name' => ['required', 'max:100', 'string', Rule::unique('permissions', 'name')
            ->where('parent_id', $this->parent_id)]
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
            'name' => __('permission_title'),
        ];
    }
}
