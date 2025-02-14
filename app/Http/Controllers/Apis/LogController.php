<?php

namespace App\Http\Controllers\Apis;

use App\Collections\Logs\LogListCollection;
use App\Http\Controllers\ApiController;
use App\Services\Logs\GetLogByCurrentOrganizationService;

class LogController extends ApiController
{
    /**
     * Get the log activities for current user
     * 
     * @return LogListCollection
     */
    public function index(): LogListCollection
    {
        $logActivities = resolve(GetLogByCurrentOrganizationService::class)->run();

        return $this->formatJson(LogListCollection::class, $logActivities);
    }
}
