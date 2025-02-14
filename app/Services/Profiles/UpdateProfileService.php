<?php

namespace App\Services\Profiles;

use App\Models\Education;
use App\Models\Relative;
use App\Models\WorkHistory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UpdateProfileService
{
    /**
     * Run the update profile for current user
     *
     * @param array $request
     * @return void
     * @throws \Exception
     */
    public function run(array $request): void
    {
        $user = auth()->user();
        $this->handleFileUpload($request, 'avatar');
        $this->handleFileUpload($request, 'front_citizen_identification_img');
        $this->handleFileUpload($request, 'back_citizen_identification_img');

        DB::beginTransaction();
        try {
            if (!empty($request['organizations'])) {
                $user->organizations()->sync($request['organizations']);
            }

            $user->relatives()->delete();
            if (!empty($request['relatives'])) {
                $this->handleUpdateRelatives($request['relatives']);
            }

            if (!empty($request['educations'])) {
                $this->handleUpdateEducations($request['educations']);
            } else {
                $user->educations()->delete();
            }

            $user->workHistories()->delete();
            if (!empty($request['work_histories'])) {
                $this->handleUpdateWorkHistories($request['work_histories']);
            }

            if (!empty($request['password'])) {
                $request['password'] = Hash::make($request['password']);
            } else {
                unset($request['password']);
            }

            $request = array_filter($request);
            $user->update($request); 
 
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Handle file upload 
     * 
     * @param array $request
     * @param string $fieldName
     * @return void
     */
    private function handleFileUpload(array &$request, string $fieldName): void
    {
        if (!empty($request[$fieldName])) {
            $fileName = time() . rand(1, 99) . '.' . $request[$fieldName]->extension();
            $request[$fieldName] = $request[$fieldName]->storeAs('images', $fileName, 'public');
        }
    }

    /**
     * Handle update relatives
     *
     * @param array $relatives
     * @return void
     */
    private function handleUpdateRelatives(array $relatives): void
    {
        $relativeArr = [];
        
        foreach ($relatives as $relative) {
            $relativeArr[] = [
                'user_id' => auth()->id(),
                'name' => $relative['name'],
                'relationship' => $relative['relationship'],
                'address' => $relative['address'],
                'phone_number' => $relative['phone_number'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        Relative::query()->insert($relativeArr);
    }

    /**
     * Handle update educations
     * 
     * @param array $educations
     * @return void
     */
    private function handleUpdateEducations(array $educations): void
    {
        foreach ($educations as $education) {
            // Handle certificate image upload
            $certificateImagePath = $this->uploadFileIfExists($education['certificate_image'] ?? null, $education['id'] ?? null);

            // Prepare data for insertion or update
            $educationData = [
                'user_id' => auth()->id(),
                'school_name' => $education['school_name'],
                'education_level' => $education['education_level'],
                'major' => $education['major'],
                'education_type' => $education['education_type'],
                'rank_level' => $education['rank_level'],
                'graduation_date' => $education['graduation_date'],
                'certificate_image' => $certificateImagePath,
            ];

            // Update if education ID exists, otherwise create new record
            if (!empty($education['id'])) {
                Education::where('id', $education['id'])->update($educationData);
            } else {
                Education::create($educationData);
            }
        }
    }

    /**
     * Handle update work histories
     * 
     * @param array $workHistories
     * @return void
     */
    public function handleUpdateWorkHistories(array $workHistories): void
    {
        $workHistoriesArr = [];

        foreach ($workHistories as $workHistory) {
            $workHistoriesArr[] = [
                'user_id' => auth()->id(),
                'start_date' => $workHistory['start_date'],
                'end_date' => $workHistory['end_date'],
                'organization_name' => $workHistory['organization_name'],
                'organization_phone_number' => $workHistory['organization_phone_number'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        WorkHistory::query()->insert($workHistoriesArr);
    }

    /**
     * Upload file if provided, otherwise return the existing file path if updating.
     *
     * @param UploadedFile|null $file
     * @param int|null $educationId
     * @return string|null
     */
    private function uploadFileIfExists(?UploadedFile $file, ?int $educationId): ?string
    {
        if ($file) {
            $fileName = time() . rand(1, 99) . '.' . $file->extension();
            return $file->storeAs('images', $fileName, 'public');
        }

        if ($educationId) {
            $existingEducation = Education::find($educationId);
            return $existingEducation ? $existingEducation->certificate_image : null;
        }

        return null;
    }

}
