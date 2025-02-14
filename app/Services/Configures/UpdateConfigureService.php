<?php

namespace App\Services\Configures;

use App\Models\Configuration;

class UpdateConfigureService
{
    /**
     * Run the update configuration
     *
     * @param array $data
     * @return void
     */
    public function run(array $data): void
    {
        $this->handleFileUpload($data, 'favicon');
        $this->handleFileUpload($data, 'logo');
        $configsToUpdate = [];
        foreach ($data as $key => $value) {
            $configsToUpdate[] = [
                'key' => $key,
                'value' => $value,
            ];
        }
        
        Configuration::query()->upsert($configsToUpdate, ['key'], ['value']);
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
}
