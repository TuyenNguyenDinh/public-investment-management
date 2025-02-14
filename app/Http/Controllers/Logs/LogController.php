<?php

namespace App\Http\Controllers\Logs;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;

class LogController extends Controller
{
    /**
     * Get the log activity page
     * 
     * @return View|Factory|Application
     */
    public function index(): View|Factory|Application
    {
        return view('content.apps.logs.list');
    }
}
