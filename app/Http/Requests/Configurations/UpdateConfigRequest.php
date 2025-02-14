<?php

namespace App\Http\Requests\Configurations;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateConfigRequest extends FormRequest
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
            'app_name' => 'nullable|string|max:255',
            'language' => 'nullable|string|in:vn,en',
            'date_format' => 'nullable|string|in:Y-m-d,d/m/Y,m/d/Y,d-m-Y,d/m/y',
            'time_format' => 'nullable|string|in:H:i,h:i A,H:i:s,h:i:s A',
            'favicon' => 'nullable|image|mimes:ico,png,jpg,jpeg|max:2048',
            'logo' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'new_post_date' => 'nullable|integer|min:1|max:20',

            'email_protocol' => 'nullable|string|in:smtp,sendmail,mail',
            'from_name' => 'nullable|string|max:255',
            'from_address' => 'nullable|email|max:255',
            'smtp_server' => 'nullable|string|max:255',
            'smtp_user' => 'nullable|string|max:255',
            'smtp_password' => 'nullable|string',
            'smtp_port' => 'nullable|integer|min:1|max:65535',
            'security_type' => 'nullable|string|in:tls,ssl,none',
            'test_email' => 'nullable|email|max:255',
            'thumbnail_post_width_size' => 'nullable|integer|min:1|max:2048',
            'thumbnail_post_height_size' => 'nullable|integer|min:1|max:2048',
        ];
    }
    
    public function attributes(): array
    {
        return [
            'app_name' => __('app_name'),
            'language' => __('language'),
            'date_format' => __('date_format'),
            'time_format' => __('time_format'),
            'favicon' => __('favicon_icon'),
            'logo' => __('logo'),
            'new_post_date' => __('new_post_date'),
            'email_protocol' => __('email_protocol'),
            'from_name' => __('from_name'),
            'from_address' => __('from_address'),
            'smtp_server' => __('smtp_server'),
            'smtp_user' => __('smtp_user'),
            'smtp_password' => __('smtp_password'),
            'smtp_port' => __('smtp_port'),
            'security_type' => __('security_type'),
            'test_email' => __('test_email'),
            'thumbnail_post_width_size' => __('thumbnail_post_width_size'),
            'thumbnail_post_height_size' => __('thumbnail_post_height_size'),
        ];
    }
}
