<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Services\Organizations\GetAllOrganizationUnitsService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;

class CategoryController extends Controller
{
    /**
     * Get the organization tree list
     *
     * @return Application|Factory|View
     */
    public function index(): Application|Factory|View
    {
        $organizations = resolve(GetAllOrganizationUnitsService::class)->run();

        return view('content.apps.categories.list', compact('organizations'));
    }
}
