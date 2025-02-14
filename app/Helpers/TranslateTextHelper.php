<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;
use Stichoza\GoogleTranslate\Exceptions\LargeTextException;
use Stichoza\GoogleTranslate\Exceptions\RateLimitException;
use Stichoza\GoogleTranslate\Exceptions\TranslationRequestException;
use Stichoza\GoogleTranslate\GoogleTranslate;

class TranslateTextHelper
{
    /**
     * @var string Default source language.
     */
    private static string $source = 'vi';

    /**
     * @var string Default target language.
     */
    private static string $target = 'en';

    /**
     * Set the source language.
     *
     * @param string|null $source The source language code.
     * @return self This instance of the class.
     */
    public static function setSource(?string $source): self
    {
        if (!empty($source)) {
            self::$source = $source;
        }

        return new self();
    }

    /**
     * Set the target language.
     *
     * @param string|null $target The target language code.
     * @return self This instance of the class.
     */
    public static function setTarget(?string $target): self
    {
        if (!empty($target)) {
           self::$target = $target;
        }

        return new self();
    }

    /**
     * Translate the given text from the source language to the target language.
     *
     * @param string $text The text to be translated.
     * @return string The translated text.
     */
    public static function translate(string $text): string
    {
        $translatedText = '';

        try {
            $translator = new GoogleTranslate();
            $translator->setSource(self::$source);
            $translator->setTarget(self::$target);

            $translatedText = $translator->translate($text);
        } catch (LargeTextException|RateLimitException|TranslationRequestException $e) {
            Log::error(logErrorMessage(
                message: $e->getMessage(),
                file: $e->getFile(),
                line: $e->getLine()
            ));
        }

        return $translatedText;
    }
}