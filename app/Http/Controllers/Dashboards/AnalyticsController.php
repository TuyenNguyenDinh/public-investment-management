<?php

namespace App\Http\Controllers\Dashboards;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    /**
     * Access the analytics page
     * 
     * @return View|Factory|Application
     */
    public function index(): View|Factory|Application
    {
        return view('content.dashboard.dashboards-analytics');
    }
}
