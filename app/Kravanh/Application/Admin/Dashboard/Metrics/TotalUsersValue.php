<?php

namespace App\Kravanh\Application\Admin\Dashboard\Metrics;

use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Value;

class TotalUsersValue extends Value
{

    public function calculate(NovaRequest $request): mixed
    {
        return 1000;//$this->count($request, User::class);
    }


    public function ranges(): array
    {
        return [
        ];
    }


    public function cacheFor()
    {
        if (!isLocal()) {
            return now()->addMinutes(5);
        }
    }

    public function uriKey(): string
    {
        return 'dashboard-total-users';
    }
}
