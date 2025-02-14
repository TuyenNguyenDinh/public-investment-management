<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\ChooseOrganizationUnitService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Access the organization auth page
     *
     * @return View|Factory|Application|RedirectResponse
     */
    public function chooseOrganization(): View|Factory|Application|RedirectResponse
    {
        $organizations = resolve(ChooseOrganizationUnitService::class)->run(auth()->id());

        return view('auth.choose-organization', compact('organizations'));
    }

    /**
     * Logout
     *
     * @param Request $request
     * @return Application|Redirector|RedirectResponse
     */
    public function logout(Request $request): Application|Redirector|RedirectResponse
    {
        Auth::logout();
        $request->session()->flush();

        return redirect('/');
    }
}
