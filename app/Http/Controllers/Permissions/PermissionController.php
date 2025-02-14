<?php

namespace App\Http\Controllers\Permissions;

use App\Http\Controllers\Controller;
use App\Http\Requests\Permissions\StorePermissionRequest;
use App\Http\Requests\Permissions\UpdatePermissionRequest;
use App\Services\Permissions\DeletePermissionByIdService;
use App\Services\Permissions\StorePermissionService;
use App\Services\Permissions\UpdatePermissionByIdService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;

class PermissionController extends Controller
{
    /**
     * Access the permission page
     *
     * @return View|Factory|Application
     */
    public function index(): View|Factory|Application
    {
        return view('content.apps.permissions.list');
    }

    /**
     * Update the permission by id
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function delete(int $id): RedirectResponse
    {
        resolve(DeletePermissionByIdService::class)->run($id);
        session()->flash('success', __('permission_delete_success'));

        return redirect()->route('app-roles-permissions-index');
    }
}
