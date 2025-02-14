<?php

namespace App\Http\Requests\Posts;

use App\Enums\Posts\PostType;
use App\Http\Requests\BaseRequests\ApiRequest;
use Illuminate\Contracts\Validation\ValidationRule;

class BulkChangeStatusPostsApiRequest extends ApiRequest
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
        $postType = implode(',', PostType::postTypeArray());

        return [
            'post_ids' => 'array',
            'status' => ['required', "in:{$postType}"],
            'note' => ['nullable', 'string', 'max:255'],
        ];
    }
}
