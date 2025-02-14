<?php

namespace App\Http\Controllers\Menus;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

class MenuController extends Controller
{
   /**
    * Get the menu tree list
    *
    * @return Application|Factory|View
    */
   public function index(): Application|Factory|View
   {
      return view('content.apps.menus.list');
   }
}
