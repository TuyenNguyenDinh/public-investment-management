<?php

namespace App\Helpers;

use App\Models\Notification;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Psr\SimpleCache\InvalidArgumentException;

class Helpers
{
    public static function appClasses(): array
    {
        $data = config('custom.custom');

        // default data array
        $DefaultData = [
            'myLayout' => 'vertical',
            'myTheme' => 'theme-default',
            'myStyle' => 'light',
            'myRTLSupport' => true,
            'myRTLMode' => true,
            'hasCustomizer' => true,
            'showDropdownOnHover' => true,
            'displayCustomizer' => true,
            'contentLayout' => 'compact',
            'headerType' => 'fixed',
            'navbarType' => 'fixed',
            'menuFixed' => true,
            'menuCollapsed' => false,
            'footerFixed' => false,
            'customizerControls' => [
                'rtl',
                'style',
                'headerType',
                'contentLayout',
                'layoutCollapsed',
                'showDropdownOnHover',
                'layoutNavbarOptions',
                'themes',
            ],
            //   'defaultLanguage'=>'en',
        ];

        // if any key missing of array from custom.php file it will be merge and set a default value from dataDefault array and store in data variable
        $data = array_merge($DefaultData, $data);

        // All options available in the template
        $allOptions = [
            'myLayout' => ['vertical', 'horizontal', 'blank', 'front'],
            'menuCollapsed' => [true, false],
            'hasCustomizer' => [true, false],
            'showDropdownOnHover' => [true, false],
            'displayCustomizer' => [true, false],
            'contentLayout' => ['compact', 'wide'],
            'headerType' => ['fixed', 'static'],
            'navbarType' => ['fixed', 'static', 'hidden'],
            'myStyle' => ['light', 'dark', 'system'],
            'myTheme' => ['theme-default', 'theme-bordered', 'theme-semi-dark'],
            'myRTLSupport' => [true, false],
            'myRTLMode' => [true, false],
            'menuFixed' => [true, false],
            'footerFixed' => [true, false],
            'customizerControls' => [],
            // 'defaultLanguage'=>array('en'=>'en','fr'=>'fr','de'=>'de','ar'=>'ar'),
        ];

        //if myLayout value empty or not match with default options in custom.php config file then set a default value
        foreach ($allOptions as $key => $value) {
            if (array_key_exists($key, $DefaultData)) {
                if (gettype($DefaultData[$key]) === gettype($data[$key])) {
                    // data key should be string
                    if (is_string($data[$key])) {
                        // data key should not be empty
                        if (isset($data[$key]) && $data[$key] !== null) {
                            // data key should not be exist inside allOptions array's sub array
                            if (!array_key_exists($data[$key], $value)) {
                                // ensure that passed value should be match with any of allOptions array value
                                $result = array_search($data[$key], $value, 'strict');
                                if (empty($result) && $result !== 0) {
                                    $data[$key] = $DefaultData[$key];
                                }
                            }
                        } else {
                            // if data key not set or
                            $data[$key] = $DefaultData[$key];
                        }
                    }
                } else {
                    $data[$key] = $DefaultData[$key];
                }
            }
        }
        $styleVal = $data['myStyle'] == "dark" ? "dark" : "light";
        $styleUpdatedVal = $data['myStyle'] == "dark" ? "dark" : $data['myStyle'];
        // Determine if the layout is admin or front based on cookies
        $layoutName = $data['myLayout'];
        $isAdmin = Str::contains($layoutName, 'front') ? false : true;

        $modeCookieName = $isAdmin ? 'admin-mode' : 'front-mode';
        $colorPrefCookieName = $isAdmin ? 'admin-colorPref' : 'front-colorPref';

        // Determine style based on cookies, only if not 'blank-layout'
        if ($layoutName !== 'blank') {
            if (isset($_COOKIE[$modeCookieName])) {
                $styleVal = $_COOKIE[$modeCookieName];
                if ($styleVal === 'system') {
                    $styleVal = $_COOKIE[$colorPrefCookieName] ?? 'light';
                }
                $styleUpdatedVal = $_COOKIE[$modeCookieName];
            }
        }

        isset($_COOKIE['theme']) ? $themeVal = $_COOKIE['theme'] : $themeVal = $data['myTheme'];

        $directionVal = isset($_COOKIE['direction']) ? ($_COOKIE['direction'] === "true" ? 'rtl' : 'ltr') : $data['myRTLMode'];

        //layout classes
        $layoutClasses = [
            'layout' => $data['myLayout'],
            'theme' => $themeVal,
            'themeOpt' => $data['myTheme'],
            'style' => $styleVal,
            'styleOpt' => $data['myStyle'],
            'styleOptVal' => $styleUpdatedVal,
            'rtlSupport' => $data['myRTLSupport'],
            'rtlMode' => $data['myRTLMode'],
            'textDirection' => $directionVal,//$data['myRTLMode'],
            'menuCollapsed' => $data['menuCollapsed'],
            'hasCustomizer' => $data['hasCustomizer'],
            'showDropdownOnHover' => $data['showDropdownOnHover'],
            'displayCustomizer' => $data['displayCustomizer'],
            'contentLayout' => $data['contentLayout'],
            'headerType' => $data['headerType'],
            'navbarType' => $data['navbarType'],
            'menuFixed' => $data['menuFixed'],
            'footerFixed' => $data['footerFixed'],
            'customizerControls' => $data['customizerControls'],
        ];

        // sidebar Collapsed
        if ($layoutClasses['menuCollapsed'] == true) {
            $layoutClasses['menuCollapsed'] = 'layout-menu-collapsed';
        }

        // Header Type
        if ($layoutClasses['headerType'] == 'fixed') {
            $layoutClasses['headerType'] = 'layout-menu-fixed';
        }
        // Navbar Type
        if ($layoutClasses['navbarType'] == 'fixed') {
            $layoutClasses['navbarType'] = 'layout-navbar-fixed';
        } elseif ($layoutClasses['navbarType'] == 'static') {
            $layoutClasses['navbarType'] = '';
        } else {
            $layoutClasses['navbarType'] = 'layout-navbar-hidden';
        }

        // Menu Fixed
        if ($layoutClasses['menuFixed']) {
            $layoutClasses['menuFixed'] = 'layout-menu-fixed';
        }


        // Footer Fixed
        if ($layoutClasses['footerFixed']) {
            $layoutClasses['footerFixed'] = 'layout-footer-fixed';
        }

        // RTL Supported template
        if ($layoutClasses['rtlSupport']) {
            $layoutClasses['rtlSupport'] = '/rtl';
        }

        // RTL Layout/Mode
        if ($layoutClasses['rtlMode']) {
            $layoutClasses['rtlMode'] = 'rtl';
            $layoutClasses['textDirection'] = isset($_COOKIE['direction']) ? ($_COOKIE['direction'] === "true" ? 'rtl' : 'ltr') : 'rtl';

        } else {
            $layoutClasses['rtlMode'] = 'ltr';
            $layoutClasses['textDirection'] = isset($_COOKIE['direction']) && $_COOKIE['direction'] === "true" ? 'rtl' : 'ltr';

        }

        // Show DropdownOnHover for Horizontal Menu
        if ($layoutClasses['showDropdownOnHover']) {
            $layoutClasses['showDropdownOnHover'] = true;
        } else {
            $layoutClasses['showDropdownOnHover'] = false;
        }

        // To hide/show display customizer UI, not js
        if ($layoutClasses['displayCustomizer']) {
            $layoutClasses['displayCustomizer'] = true;
        } else {
            $layoutClasses['displayCustomizer'] = false;
        }

        return $layoutClasses;
    }

    public static function updatePageConfig($pageConfigs): void
    {
        $demo = 'custom';
        if (isset($pageConfigs)) {
            if (count($pageConfigs) > 0) {
                foreach ($pageConfigs as $config => $val) {
                    Config::set('custom.' . $demo . '.' . $config, $val);
                }
            }
        }
    }

    /**
     * Create a new notification item.
     *
     * @param array $data {
     *     @var int $user_id
     *     @var int $target_id
     *     @var string $target_type
     *     @var string $title
     *     @var string $title_en
     *     @var string|null $content
     *     @var string|null $content_en
     *     @var string $status
     * }
     */
    public static function pushNotifications(array $data): void
    {
        Notification::query()->insert($data);
    }

    /**
     * Prepare an array of data for creating a notification item.
     *
     * @param int         $user_id
     * @param int         $target_id
     * @param string      $target_type
     * @param string      $title
     * @param string      $title_en
     * @param string|null $content
     * @param string|null $content_en
     * @param string      $status
     *
     * @return array
     */
    public static function prepareItemNotification(
        int         $user_id,
        int         $target_id,
        string      $target_type,
        string      $title,
        string      $title_en,
        string|null $content = null,
        string|null $content_en = null,
        string      $status = 'unread'
    ): array
    {
        return [
            'user_id' => $user_id,
            'target_id' => $target_id,
            'target_type' => $target_type,
            'title' => $title,
            'title_en' => $title_en,
            'content' => $content,
            'content_en' => $content_en,
            'status' => $status,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
    
    /**
     * Write a value to Redis cache for a given number of minutes.
     *
     * @param string $key
     * @param mixed $value
     * @param int|null $minutes
     *
     * @return void
     */
    public static function writeCache(string $key, mixed $value, ?int $minutes = 60): void {
        Cache::store('redis')->put($key, $value, now()->addMinutes($minutes));
    }


    /**
     * Read a value from Redis cache.
     *
     * @param string $key
     *
     * @return mixed
     * @throws InvalidArgumentException
     */
    public static function readCache(string $key): mixed {
        return Cache::store('redis')->get($key);
    }
}
