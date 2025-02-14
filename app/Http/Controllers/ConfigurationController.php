<?php

namespace App\Http\Controllers;

use App\Http\Requests\Configurations\UpdateConfigRequest;
use App\Services\Configures\GetAllConfigureService;
use App\Services\Configures\UpdateConfigureService;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class ConfigurationController extends Controller
{
    /**
     * Get all configuration
     * 
     * @return View|Factory|Application
     */
    public function index(): View|Factory|Application
    {
        $config = resolve(GetAllConfigureService::class)->run();

        return view('content.apps.configs.list', compact('config'));
    }

    /**
     * Update configuration
     * 
     * @param UpdateConfigRequest $request
     * @return RedirectResponse
     */
    public function update(UpdateConfigRequest $request): RedirectResponse
    {
        $request = $request->validated();
        resolve(UpdateConfigureService::class)->run($request);
        session()->flash('success', __('update_config_success', locale: $request['language'] ?? App::getLocale()));

        return redirect()->route('app-configs-index');
    }
}
