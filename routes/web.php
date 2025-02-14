<?php

use App\Http\Controllers\Apis\CategoryController as ApiCategoryController;
use App\Http\Controllers\Apis\LogActivityController as ApiLogActivityController;
use App\Http\Controllers\Apis\LogController as ApiLogController;
use App\Http\Controllers\Apis\MenuController as ApiMenuController;
use App\Http\Controllers\Apis\NotificationController as ApiNotificationController;
use App\Http\Controllers\Apis\OrganizationUnitController as ApiOrganizationUnitController;
use App\Http\Controllers\Apis\PermissionController as ApiPermissionController;
use App\Http\Controllers\Apis\PostController as ApiPostController;
use App\Http\Controllers\Apis\ProfileController as ApiProfileController;
use App\Http\Controllers\Apis\RoleController as ApiRoleController;
use App\Http\Controllers\Apis\StorageController as ApiStorageController;
use App\Http\Controllers\Apis\UserController as ApiUserController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Category\CategoryController;
use App\Http\Controllers\ConfigurationController;
use App\Http\Controllers\Dashboards\AnalyticsController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\LogActivities\LogActivityController;
use App\Http\Controllers\Logs\LogController;
use App\Http\Controllers\Menus\MenuController;
use App\Http\Controllers\Organizations\OrganizationUnitController;
use App\Http\Controllers\Permissions\PermissionController;
use App\Http\Controllers\PostManagement\PostController;
use App\Http\Controllers\Profiles\ProfileController;
use App\Http\Controllers\Roles\RoleController;
use App\Http\Controllers\Users\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:web', config('jetstream.auth_session'), 'verified'])->group(function () {
    // Choose organization authentication
    Route::get('/choose-organization', [AuthController::class, 'chooseOrganization'])->name('choose-organization-view');
    // Logout
    Route::middleware('log_activity')->group(function () {
        Route::get('/lang/{locale}', [LanguageController::class, 'change'])->name('lang.change');

        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

        Route::get('/', [AnalyticsController::class, 'index'])->middleware([config('jetstream.check_organization'), 'permission:Access Dashboards'])
            ->name('dashboard-analytics');
        // App prefix
        Route::prefix('app')->middleware(config('jetstream.check_organization'))->name('app-')->group(function (): void {
            // Users
            Route::prefix('users')->name('users-')->group(function (): void {
                Route::get('/', [UserController::class, 'index'])->middleware('permission:Access Users')->name('index');
            });
            // Roles
            Route::prefix('roles')->name('roles-')->group(function (): void {
                Route::get('/', [RoleController::class, 'index'])->middleware('permission:Access Roles')->name('index');
                // Permission
                Route::prefix('permissions')->name('permissions-')
                    ->group(function (): void {
                        Route::get('/', [PermissionController::class, 'index'])->middleware('permission:Access Permissions')->name('index');
                        Route::delete('/{id}', [PermissionController::class, 'delete'])->middleware('permission:Delete Permissions')
                            ->name('delete')->where('id', '[0-9]+');
                    });
            });
            Route::prefix('organizations')->name('organizations-')->middleware('permission:Access Organizations')
                ->group(function (): void {
                    Route::get('/', [OrganizationUnitController::class, 'index'])->name('index');
                });
            Route::prefix('menus')->name('menus-')->middleware('permission:Access Menus')->group(function (): void {
                Route::get('/', [MenuController::class, 'index'])->name('index');
            });

            Route::prefix('profiles')->name('profiles-')->group(function (): void {
                Route::get('/', [ProfileController::class, 'index'])->name('index');
            });

            Route::prefix('log_activities')->name('log-activities-')->group(function (): void {
                Route::get('/', [LogActivityController::class, 'index'])->name('index');
            });

            Route::prefix('logs')->name('logs-')->middleware('permission:Access Logs')
                ->group(function (): void {
                    Route::get('/', [LogController::class, 'index'])->name('index');
                });

            Route::prefix('posts')->name('posts-')->group(function (): void {
                Route::get('/', [PostController::class, 'index'])
                    ->middleware('permission:Access Posts')
                    ->name('index');
                Route::get('/create', [PostController::class, 'create'])
                    ->middleware('permission:Create Posts')
                    ->name('create');
                Route::post('/', [PostController::class, 'store'])
                    ->middleware('permission:Create Posts')
                    ->name('store');
                Route::get('/detail/{slug}', [PostController::class, 'show'])
                    ->middleware(['permission:Access Posts', 'user_viewed_post'])
                    ->name('detail');
                Route::patch('/update/{slug}', [PostController::class, 'update'])
                    ->middleware('permission:Update Posts')
                    ->name('update');
                Route::post('/import-excel', [PostController::class, 'importExcel'])
                    ->middleware('permission:Import Posts')
                    ->name('import-excel');
                Route::post('/export-excel', [PostController::class, 'exportExcel'])
                    ->middleware('permission:Export Posts')
                    ->name('export-excel');
            });

            Route::prefix('categories')->name('categories-')
                ->middleware('permission:Access Categories')
                ->group(function (): void {
                    Route::get('/', [CategoryController::class, 'index'])->name('index');
                });

            Route::prefix('configs')->name('configs-')->middleware('permission:Access Configs')->group(function (): void {
                Route::get('/', [ConfigurationController::class, 'index'])->name('index');
                Route::patch('/', [ConfigurationController::class, 'update'])->name('update');
            });
        });
    });

    Route::prefix('api/v1')->name('api.')->group(function (): void {
        Route::post('/choose-organization', [\App\Http\Controllers\Apis\AuthController::class, 'chooseOrganization'])
            ->name('choose-organization');

        Route::prefix('permissions')->middleware('permission:Access Permissions')->group(function () {
            Route::get('/', [ApiPermissionController::class, 'index']);
            Route::post('/', [ApiPermissionController::class, 'store'])->middleware('permission:Create Permissions')->name('store');
            Route::put('/{id}', [ApiPermissionController::class, 'update'])->middleware('permission:Update Permissions')
                ->name('update')->where('id', '[0-9]+');
            Route::post('/children', [ApiPermissionController::class, 'storeChildren'])->middleware('log_activity')
                ->name('children.store');
        });

        Route::prefix('users')->name('users.')->group(function (): void {
            Route::get('/', [ApiUserController::class, 'index'])->middleware('permission:Access Users');
            Route::middleware('log_activity')->group(function () {
                Route::post('/', [ApiUserController::class, 'store'])->middleware('permission:Create Users');
                Route::get('/{id}', [ApiUserController::class, 'show'])->middleware('permission:Access Users');
                Route::patch('/{id}', [ApiUserController::class, 'update'])->middleware('permission:Update Users')
                    ->where('id', '[0-9]+');
                Route::delete('/{id}', [ApiUserController::class, 'delete'])->middleware('permission:Delete Users')
                    ->where('id', '[0-9]+');
                Route::put('/{id}/triggers', [ApiUserController::class, 'triggers'])->middleware('role:Admin');
            });
        });

        Route::prefix('roles')->name('roles.')->group(function (): void {
            Route::get('/', [ApiRoleController::class, 'index'])->middleware('permission:Access Roles')
                ->name('index');
            Route::middleware('log_activity')->group(function () {
                Route::get('/{id}', [ApiRoleController::class, 'show'])->middleware('permission:Access Roles')
                    ->name('show');
                Route::post('/', [ApiRoleController::class, 'store'])->middleware('permission:Create Roles')
                    ->name('store');
                Route::patch('/{id}', [ApiRoleController::class, 'update'])->middleware('permission:Update Roles')
                    ->name('update')
                    ->where('id', '[0-9]+');
                Route::delete('/{id}', [ApiRoleController::class, 'delete'])->middleware('permission:Delete Roles')
                    ->name('delete')
                    ->where('id', '[0-9]+');
            });
        });

        Route::prefix('organizations')->name('organizations.')->group(function (): void {
            Route::get('/', [ApiOrganizationUnitController::class, 'index'])->middleware('permission:Access Organizations')
                ->name('index');
            Route::middleware('log_activity')->group(function () {
                Route::post('/', [ApiOrganizationUnitController::class, 'store'])->middleware('permission:Create Organizations')
                    ->name('store');
                Route::get('/{id}', [ApiOrganizationUnitController::class, 'show'])->middleware('permission:Access Organizations')
                    ->name('show')
                    ->where('id', '[0-9]+');
                Route::put('/{id}', [ApiOrganizationUnitController::class, 'update'])->middleware('permission:Update Organizations')
                    ->name('update')
                    ->where('id', '[0-9]+');
                Route::delete('/{id}', [ApiOrganizationUnitController::class, 'delete'])->middleware('permission:Delete Organizations')
                    ->name('delete')
                    ->where('id', '[0-9]+');
                Route::get('roles', [ApiOrganizationUnitController::class, 'roleByOrganizationIds'])
                    ->name('roles.index');
            });
        });

        Route::prefix('menus')->name('menus.')->group(function (): void {
            Route::get('/', [ApiMenuController::class, 'index'])->middleware('permission:Access Menus')
                ->name('index');
            Route::middleware('log_activity')->group(function () {
                Route::post('/', [ApiMenuController::class, 'store'])->middleware('permission:Create Menus')
                    ->name('store');
                Route::put('/bulk-update', [ApiMenuController::class, 'bulkUpdate'])->middleware('permission:Update Menus')
                    ->name('bulk-update');
                Route::get('/{id}', [ApiMenuController::class, 'show'])->middleware('permission:Access Menus')
                    ->where('id', '[0-9]+')
                    ->name('show');
                Route::put('/{id}', [ApiMenuController::class, 'update'])->middleware('permission:Update Menus')
                    ->where('id', '[0-9]+')
                    ->name('update');
                Route::delete('/{id}', [ApiMenuController::class, 'delete'])->middleware('permission:Delete Menus')
                    ->where('id', '[0-9]+')
                    ->name('delete');
            });
        });

        Route::prefix('profiles')->name('profiles.')->middleware('log_activity')->group(function (): void {
            Route::patch('/', [ApiProfileController::class, 'update'])->name('update');
            Route::delete('/education/{id}', [ApiProfileController::class, 'deleteEducation'])->name('destroy.education');
        });

        Route::prefix('log_activities')->name('log_activities.')->group(function (): void {
            Route::get('/', [ApiLogActivityController::class, 'index'])->name('index');
        });

        Route::prefix('logs')->name('logs.')->middleware('permission:Access Logs')
            ->group(function (): void {
                Route::get('/', [ApiLogController::class, 'index'])->name('index');
            });

        Route::prefix('storage')->name('storage.')->group(function (): void {
            Route::prefix('post')->name('post.')->middleware(['permission:Create Posts', 'permission:Update Posts'])
                ->group(function (): void {
                    Route::post('/upload-image-content', [ApiStorageController::class, 'uploadImageContentPost'])
                        ->name('upload.image.content');
                });
        });

        Route::prefix('posts')->name('posts.')->group(function (): void {
            Route::post('/', [ApiPostController::class, 'index'])->middleware('permission:Access Posts')
                ->name('index');
            Route::post('/bulk-delete', [ApiPostController::class, 'bulkDelete'])->middleware('permission:Delete Posts')
                ->name('bulk-delete');
            Route::post('/store', [ApiPostController::class, 'store'])->name('store');
            Route::post('/bulk-change-status', [ApiPostController::class, 'bulkChangeStatus'])
                ->middleware('permission:Update Posts')
                ->name('bulk-change-status');
        });

        Route::prefix('categories')->name('categories.')->group(function (): void {
            Route::get('', [ApiCategoryController::class, 'index'])->middleware('permission:Access Categories')
                ->name('index');
            Route::post('/tree', [ApiCategoryController::class, 'tree'])->middleware('permission:Access Categories')
                ->name('tree');
            Route::get('/{id}', [ApiCategoryController::class, 'show'])->middleware('permission:Access Categories')
                ->name('index');
            Route::put('/{id}', [ApiCategoryController::class, 'update'])->middleware('permission:Update Categories')
                ->name('update')
                ->where('id', '[0-9]+');
            Route::delete('/{id}', [ApiCategoryController::class, 'delete'])->middleware('permission:Delete Categories')
                ->name('delete')
                ->where('id', '[0-9]+');
            Route::post('/', [ApiCategoryController::class, 'store'])->middleware('permission:Create Categories')
                ->name('store');
        });

        Route::prefix('notifications')->name('notifications.')->group(function (): void {
            Route::get('/', [ApiNotificationController::class, 'index'])->name('index');
            Route::put('/mark-as-read', [ApiNotificationController::class, 'markAsRead'])->name('mark-as-read');
        });
    });

    Route::any('/ckfinder/connector', '\CKSource\CKFinderBridge\Controller\CKFinderController@requestAction')
        ->name('ckfinder_connector');

    Route::any('/ckfinder/browser', '\CKSource\CKFinderBridge\Controller\CKFinderController@browserAction')
        ->name('ckfinder_browser');
});
