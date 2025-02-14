<?php

namespace App\Http\Requests\Posts;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg|max:500',
            'categories' => 'required|array',
            'organizations' => 'required|array',
            'scheduled_date' => 'nullable|date|after_or_equal:now',
            'content' => 'string'
        ];
    }

    public function attributes()
    {
        return [
            'title' => __('title'),
            'thumbnail' => __('thumbnail'),
            'categories' => __('Categories'),
            'organizations' => __('organizations'),
            'scheduled_date' => __('schedule_date'),
            'content' => __('content')
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->scheduled_date) {
            $this->merge([
                'scheduled_date' => date('Y-m-d H:i:s', strtotime($this->scheduled_date))
            ]);
        }
    }
}
