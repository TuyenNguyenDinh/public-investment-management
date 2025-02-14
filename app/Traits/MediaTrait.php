<?php

namespace App\Traits;

use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

trait MediaTrait
{
    /**
     * Get public url.
     *
     * @param string|null $path
     * @param string $disk
     * @return null|string
     */
    public function getFileUrl(?string $path, string $disk = 'public'): ?string
    {
        try {
            $storage = Storage::disk($disk);

            return $path && $storage->exists($path)
                ? $storage->url($path)
                : null;
        } catch (Exception $e) {
            Log::error(
                logErrorMessage(
                    message: "[ERROR_GET_FILE_PUBLIC_URL]",
                    file: $e->getFile(),
                    line: $e->getLine()
                )
            );
            return null;
        }
    }

    /**
     * @param string|array|null $path
     * @param string $disk
     * @return bool
     */
    public function deleteFile(string|array|null $path, string $disk = 'public'): bool
    {
        try {
            return $path && Storage::disk($disk)->delete($path);
        } catch (Exception $e) {
            Log::error(
                logErrorMessage(
                    message: '[ERROR_DELETE_STORAGE_PATH]',
                    file: $e->getFile(),
                    line: $e->getLine()
                )
            );
            return false;
        }
    }

    /**
     * Get file name.
     *
     * @param UploadedFile $file
     * @param boolean $rename
     * @return string
     */
    public function getFileName(UploadedFile $file, bool $rename = true): string
    {
        $ext = $file->getClientOriginalExtension();
        $fileName = $rename
            ? randomString()
            : pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

        return $this->formatFileName(pathinfo($fileName, PATHINFO_FILENAME), $ext);
    }

    /**
     * Format file name.
     *
     * @param string $name Filename
     * @param string $ext ext
     *
     * @return string
     */
    public function formatFileName(string $name, string $ext): string
    {
        return sprintf('%s_%s.%s', $name, time(), $ext);
    }
}