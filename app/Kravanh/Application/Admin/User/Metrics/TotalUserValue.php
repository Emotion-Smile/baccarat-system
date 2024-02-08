<?php

namespace App\Kravanh\Application\Admin\User\Metrics;

use App\Models\User;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Value;

class TotalUserValue extends Value
{
    public $name = 'Total Users';

    public function calculate(NovaRequest $request): mixed
    {
        return $this->count($request, User::class);
    }

    public function ranges(): array
    {
        return [];
    }

    public function cacheFor()
    {
        // return now()->addMinutes(5);
    }


    public function uriKey(): string
    {
        return 'user-user-total';
    }
}
