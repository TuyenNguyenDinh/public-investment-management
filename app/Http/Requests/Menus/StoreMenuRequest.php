<?php

namespace App\Http\Requests\Menus;

use App\Enums\Menus\MenuGroupFlagEnum;
use App\Http\Requests\BaseRequests\ApiRequest;
use Illuminate\Contracts\Validation\ValidationRule;

class StoreMenuRequest extends ApiRequest
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
         'name' => 'required|string|unique:menus,name|max:50',
         'group_menu_flag' => 'boolean'
      ];

      if (!$groupFlag) {
         $appendRule = [
            'parent_id' => 'bail|nullable|exists:menus,id',
            'icon' => 'required|string|max:50',
            'slug' => 'required|string|max:100',
            'url' => 'required|string|max:100',
         ];
      }

      return array_merge($rules, $appendRule);
   }
}
