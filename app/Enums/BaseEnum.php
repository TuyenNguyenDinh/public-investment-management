<?php

namespace App\Enums;

enum BaseEnum
{
    const ROLE_ACTION = [
        'Access', 'Create', 'Update', 'Delete', 'Export', 'Import', 'Review'
    ];

    const ORGANIZATIONS = [
        'ACCESS' => 'Access Organizations',
        'CREATE' => 'Create Organizations',
        'UPDATE' => 'Update Organizations',
        'DELETE' => 'Delete Organizations',
        'EXPORT' => 'Export Organizations',
        'IMPORT' => 'Import Organizations',
    ];
    const USERS = [
        'ACCESS' => 'Access Users',
        'CREATE' => 'Create Users',
        'UPDATE' => 'Update Users',
        'DELETE' => 'Delete Users',
        'EXPORT' => 'Export Users',
        'IMPORT' => 'Import Users',
    ];

    const MENUS = [
        'ACCESS' => 'Access Menus',
        'CREATE' => 'Create Menus',
        'UPDATE' => 'Update Menus',
        'DELETE' => 'Delete Menus',
    ];

    const ROLES = [
        'ACCESS' => 'Access Roles',
        'CREATE' => 'Create Roles',
        'UPDATE' => 'Update Roles',
        'DELETE' => 'Delete Roles',
        'EXPORT' => 'Export Roles',
        'IMPORT' => 'Import Roles',
    ];

    const PERMISSIONS = [
        'ACCESS' => 'Access Permissions',
        'CREATE' => 'Create Permissions',
        'UPDATE' => 'Update Permissions',
        'DELETE' => 'Delete Permissions',
        'EXPORT' => 'Export Permissions',
        'IMPORT' => 'Import Permissions',
    ];

    const POST = [
        'ACCESS' => 'Access Posts',
        'CREATE' => 'Create Posts',
        'UPDATE' => 'Update Posts',
        'DELETE' => 'Delete Posts',
        'EXPORT' => 'Export Posts',
        'IMPORT' => 'Import Posts',
        'REVIEW' => 'Review Posts',
    ];

    const CATEGORY = [
        'ACCESS' => 'Access Categories',
        'CREATE' => 'Create Categories',
        'UPDATE' => 'Update Categories',
        'DELETE' => 'Delete Categories',
    ];

    const CONFIG = [
        'ACCESS' => 'Access Configs',
        'UPDATE' => 'Update Configs',
    ];
    const ACTIVE = 1;
    const INACTIVE = 0;
    const ACTIVE_TEXT = 'Active';
    const INACTIVE_TEXT = 'Inactive';

    /**
     * Get user status text
     * 
     * @param int $status
     * @return string
     */
    public static function getActiveByStatus(int $status): string
    {
        $statusText = self::INACTIVE_TEXT;
        if (self::ACTIVE === $status) {
            $statusText = self::ACTIVE_TEXT;
        }

        return $statusText;
   }
}
