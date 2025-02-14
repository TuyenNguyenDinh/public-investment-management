<?php

namespace App\Http\Controllers\Organizations;

use App\Http\Controllers\Controller;
use App\Http\Requests\Organizations\StoreOrganizationUnitRequest;
use App\Services\Organizations\GetOrganizationUnitService;
use App\Services\Organizations\StoreOrganizationUnitService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class OrganizationUnitController extends Controller
{
   /**
    * Get the organization tree list
    * 
    * @return Application|Factory|View
    */
   public function index(): Application|Factory|View
   {
      return view('content.apps.organizations.list');
   }
}
