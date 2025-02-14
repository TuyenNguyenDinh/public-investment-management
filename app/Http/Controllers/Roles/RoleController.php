<?php

namespace App\Http\Controllers\Roles;

use App\Http\Controllers\Controller;
use App\Services\Organizations\GetAllOrganizationUnitsService;
use App\Services\Permissions\GetPermissionWithChildService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;

class RoleController extends Controller
{
    /**
     * Access the role list
     *
     * @return View|Factory|Application
     */
    public function index(): View|Factory|Application
    {
        $permissionList = resolve(GetPermissionWithChildService::class)->run();
        $organizations = resolve(GetAllOrganizationUnitsService::class)->run();

        return view('content.apps.roles.list', compact('permissionList', 'organizations'));
    }
}
