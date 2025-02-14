<?php

namespace App\Http\Controllers\LogActivities;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

class LogActivityController extends Controller
{
    /**
     * Get the log activity page
     * 
     * @return View|Factory|Application
     */
    public function index(): View|Factory|Application
    {
        return view('content.apps.activities.list');
    }
}
