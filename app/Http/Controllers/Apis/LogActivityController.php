<?php

namespace App\Http\Controllers\Apis;

use App\Collections\LogActivities\LogActivityListCollection;
use App\Http\Controllers\ApiController;
use App\Services\LogActivities\GetLogActivityByCurrentUserService;
use Illuminate\Http\Request;

class LogActivityController extends ApiController
{
    /**
     * Get the log activities for current user
     * 
     * @return LogActivityListCollection
     */
    public function index(): LogActivityListCollection
    {
        $logActivities = resolve(GetLogActivityByCurrentUserService::class)->run();

        return $this->formatJson(LogActivityListCollection::class, $logActivities);
    }
}
