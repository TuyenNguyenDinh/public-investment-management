<?php

namespace App\Http\Requests\Posts;

use App\Http\Requests\BaseRequests\ApiRequest;
use Illuminate\Contracts\Validation\ValidationRule;

class BulkDeletePostsApiRequest extends ApiRequest
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
            'post_ids' => 'array',
        ];
    }
}
