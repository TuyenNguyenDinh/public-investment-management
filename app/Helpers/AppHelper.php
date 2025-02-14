<?php

use Carbon\Carbon;
use Hashids\Hashids;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

if ( ! function_exists('logErrorMessage')) {
    /**
     * @param string $message
     * @param string $file
     * @param int|string $line
     * @return array|string
     */
    function logErrorMessage(string $message, string $file = '', int|string $line = ''): array|string
    {
        return "\n【ERROR】: {$message} \n【File】: {$file} \n【Line】: {$line}";
    }
}

if ( ! function_exists('randomString')) {

    /**
     * Generate random string
     */
    function randomString(): string
    {
        return md5(uniqid(mt_rand() . time(), true));
    }
}

if ( ! function_exists('switchFieldByLang')) {

    /**
     * Generate random string
     */
    function switchFieldByLang(mixed $vnField, mixed $enField): ?string
    {
        $default = $vnField;

        return match (app()->getLocale()) {
            'en' => !empty($enField) ? $enField : $default,
            default => $vnField,
        };
    }
}

if (! function_exists('encodeIntId')) {
    /**
     * Encode any int id by Hashids.
     */
    function encodeIntId(int $id, string $salt = '', int $length = 12): string
    {
        return (new Hashids($salt, $length))->encode($id);
    }
}

if (! function_exists('decodeIntId')) {
    /**
     * Decode any int id has been encode by Hashids.
     */
    function decodeIntId(string $encodedId, string $salt = '', int $length = 12): ?int
    {
        $decodeArr = (new Hashids($salt, $length))->decode($encodedId);

        return !empty($decodeArr)
            ? $decodeArr[0]
            : null;
    }
}

if ( ! function_exists('hasValuesInArray')) {
    /**
     * Check if all values in the array are not null.
     */
    function hasValuesInArray(array $array): bool
    {
        return count(Arr::whereNotNull($array));
    }
}

if ( ! function_exists('formatDateTime')) {
    /**
     * parse date with format
     *
     * @param Carbon|string|null $dateTime
     * @param string $format
     * @return string|null
     */

    function formatDateTime(Carbon|string|null $dateTime, string $format = 'd/m/Y'): ?string
    {
        try {
            return $dateTime ? Carbon::parse($dateTime)->format($format) : null;
        } catch (Exception $e) {
            Log::error(logErrorMessage(
                message: $e->getMessage(),
                file: $e->getFile(),
                line: $e->getLine()
            ));

            return null;
        }
    }
}

if ( ! function_exists('formatDateTimeWithLocale')) {
    /**
     * parse date with format
     *
     * @param string|null $dateTime
     * @param bool $timestamp
     * @return string|null
     */

    function formatDateTimeWithLocale(?string $dateTime, bool $timestamp = false): ?string
    {
        try {
            $locale = app()->getLocale();
            $dateFormat = $locale === 'vn' ? 'd-m-Y' : 'Y-m-d';
            $dateFormat .= $timestamp ? ' H:i' : '';

            return $dateTime ? date($dateFormat, strtotime($dateTime)) : null;
        } catch (Exception $e) {
            Log::error(logErrorMessage(
                message: $e->getMessage(),
                file: $e->getFile(),
                line: $e->getLine()
            ));

            return null;
        }
    }
}

if ( ! function_exists('diffDay')) {
    /**
     * parse date with format
     *
     * @param Carbon|string|null $dateTime
     * @return string|float|int|null
     */

    function diffDay(Carbon|string|null $dateTime): string|float|int|null
    {
        try {
            if (empty($dateTime)) {
                return 0;
            }

            return max(today()->diffInDays($dateTime), 0);
        } catch (Exception $e) {
            Log::error(logErrorMessage(
                message: $e->getMessage(),
                file: $e->getFile(),
                line: $e->getLine()
            ));

            return 0;
        }
    }
}

if (!function_exists('isImageUrl')) {
    /**
     * @param string|null $url
     * @return bool
     */
    function isImageUrl(?string $url): bool
    {
        if (empty($url)) {
            return false;
        }

        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'svg'];
        $extension = strtolower(pathinfo($url, PATHINFO_EXTENSION));

        return in_array($extension, $imageExtensions);
    }
}