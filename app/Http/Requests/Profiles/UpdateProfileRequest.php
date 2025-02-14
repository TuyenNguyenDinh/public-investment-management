<?php

namespace App\Http\Requests\Profiles;

use App\Http\Requests\BaseRequests\ApiRequest;
use Illuminate\Contracts\Validation\ValidationRule;

class UpdateProfileRequest extends ApiRequest
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
        $userId = auth()->id();

        return [
            'name' => 'required|string|max:255|unique:users,name,' . $userId,
            'email' => 'required|email|max:255|unique:users,email,' . $userId,
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'sex' => 'nullable|in:male,female,other',
            'date_of_birth' => 'nullable|date|date_format:Y-m-d',
            'citizen_identification' => 'nullable|string|max:20|unique:users,citizen_identification,' . $userId,
            'front_citizen_identification_img' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'back_citizen_identification_img' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'phone_number' => 'nullable|string|max:15|unique:users,phone_number,' . $userId,
            'hometown' => 'nullable|string|max:255',
            'permanent_address' => 'nullable|string|max:255',
            'temporary_address' => 'nullable|string|max:255',
            'education_level' => 'nullable|string|max:100',
            'health_status' => 'nullable|string|max:100',
            'height' => 'nullable|integer|min:0',
            'weight' => 'nullable|integer|min:0',
            'organizations' => 'nullable|array',
            'relatives' => 'nullable|array',
            'educations' => 'nullable|array',
            'work_histories' => 'nullable|array',
            'password' => 'nullable|string|min:6',
            'confirm_password' => 'nullable|string|min:6|same:password',
        ];
    }
    
    public function attributes()
    {
        return [
            'name' => __('name'),
            'email' => __('email'),
            'avatar' => __('avatar'),
            'sex' => __('sex'),
            'date_of_birth' => __('date_of_birth'),
            'citizen_identification' => __('citizen_identification'),
            'front_citizen_identification_img' => __('front_citizen_identification'),
            'back_citizen_identification_img' => __('back_citizen_identification'),
            'phone_number' => __('phone_number'),
            'hometown' => __('hometown'),
            'permanent_address' => __('permanent_address'),
            'temporary_address' => __('temporary_address'),
            'education_level' => __('education_level'),
            'health_status' => __('health_status'),
            'height' => __('height'),
            'weight' => __('weight'),
            'organizations' => __('organizations'),
            'relatives' => __('relatives'),
            'educations' => __('educations'),
            'work_histories' => __('work_histories'),
            'password' => __('password'),
            'confirm_password' => __('confirm_password'),
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->date_of_birth) {
            $this->merge([
                'date_of_birth' => date('Y-m-d', strtotime($this->date_of_birth)),
            ]);
        }
        
        if ($this->work_histories) {
            $workHistories = $this->work_histories;

            foreach ($workHistories as &$workHistory) {
                if (!empty($workHistory['start_date'])) {
                    $workHistory['start_date'] = date('Y-m-d', strtotime($workHistory['start_date']));
                }
                if (!empty($workHistory['end_date'])) {
                    $workHistory['end_date'] = date('Y-m-d', strtotime($workHistory['end_date']));
                }
            }

            $this->merge([
                'work_histories' => $workHistories,
            ]);
        }
        
        if ($this->educations) {
            $educations = $this->educations;

            foreach ($educations as &$education) {
                if (!empty($education['graduation_date'])) {
                    $education['graduation_date'] = date('Y-m-d', strtotime($education['graduation_date']));
                }
            }

            $this->merge([
                'educations' => $educations,
            ]);
        }
    }
}
