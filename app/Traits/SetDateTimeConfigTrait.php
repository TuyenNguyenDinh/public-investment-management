<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;

trait SetDateTimeConfigTrait
{
    public function createdAt(): Attribute
    {
        $dateFormat = config('app.date_format') ?? 'Y-m-d';
        $timeFormat = config('app.time_format') ?? 'H:i:s';
        return new Attribute(get: fn() => Carbon::parse($this->attributes['created_at'])
            ->format("$dateFormat $timeFormat"));
    }

    public function updatedAt(): Attribute
    {
        $dateFormat = config('app.date_format') ?? 'Y-m-d';
        $timeFormat = config('app.time_format') ?? 'H:i:s';
        return new Attribute(get: fn() => Carbon::parse($this->attributes['updated_at'])
            ->format("$dateFormat $timeFormat"));
    }

    public function scheduledDate(): Attribute
    {
        $dateFormat = config('app.date_format') ?? 'Y-m-d';
        $timeFormat = config('app.time_format') ?? 'H:i:s';
        return new Attribute(get: fn() => Carbon::parse($this->attributes['scheduled_date'])
            ->format("$dateFormat $timeFormat"));
    }
}
