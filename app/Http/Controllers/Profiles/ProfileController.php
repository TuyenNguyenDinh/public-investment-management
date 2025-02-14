<?php

namespace App\Http\Controllers\Profiles;

use App\Http\Controllers\Controller;
use App\Services\Organizations\GetAllOrganizationUnitsService;
use App\Services\Profiles\GetProfileByCurrentUserService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;

class ProfileController extends Controller
{
    /**
     * Get the profile of current login user
     *
     * @return View|Factory|Application
     */
    public function index(): View|Factory|Application
    {
        $profile = resolve(GetProfileByCurrentUserService::class)->run();
        $organizations = resolve(GetAllOrganizationUnitsService::class)->run();

        return view('content.apps.profiles.index', compact('profile', 'organizations'));
    }
}
