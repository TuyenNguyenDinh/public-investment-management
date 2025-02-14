<?php

namespace App\Http\Controllers\Users;

use App\Enums\BaseEnum;
use App\Http\Controllers\Controller;
use App\Services\Menus\GetMenuListByCurrentUserService;
use App\Services\Organizations\GetAllOrganizationUnitsService;
use App\Services\Roles\CountUserByConditionService;
use App\Services\Roles\GetRoleListService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;

class UserController extends Controller
{
    /**
     * Access the user list
     *
     * @return View|Factory|Application
     */
    public function index(): View|Factory|Application
    {
        $countUserByConditionService = app(CountUserByConditionService::class);
        $totalUser = auth()->user()->hasRole('Admin') ? $countUserByConditionService->run()->count() : 0;
        $totalAdmin = auth()->user()->hasRole('Admin') ? $countUserByConditionService->run()->role('Admin')->count() : 0;
        $totalActive = auth()->user()->hasRole('Admin') ? $countUserByConditionService->run(['is_active' => BaseEnum::ACTIVE])->count() : 0;
        $totalInactive = auth()->user()->hasRole('Admin') ? $countUserByConditionService->run(['is_active' => BaseEnum::INACTIVE])->count() : 0;
        $roles = app(GetRoleListService::class)->run();
        $organizations = app(GetAllOrganizationUnitsService::class)->run();
        $menus = resolve(GetMenuListByCurrentUserService::class)->run();

        return view('content.apps.users.list', compact(
                'roles',
                'organizations',
                'totalUser',
                'totalActive',
                'totalInactive',
                'totalAdmin', 'menus')
        );
    }
}
