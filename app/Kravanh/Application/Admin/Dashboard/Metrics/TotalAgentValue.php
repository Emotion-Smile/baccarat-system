<?php

namespace App\Kravanh\Application\Admin\Dashboard\Metrics;

use App\Kravanh\Domain\User\Supports\Enums\UserType;
use App\Models\User;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Value;
use Laravel\Nova\Metrics\ValueResult;

class TotalAgentValue extends Value
{

    public $name = 'Agent';

    public function calculate(NovaRequest $request): mixed
    {
        return new ValueResult(User::where('type', UserType::AGENT)->active()->count());
    }


    public function ranges()
    {
        return [];
    }


    public function cacheFor()
    {
        if (!isLocal()) {
            return now()->addMinutes(5);
        }
    }

    public function uriKey(): string
    {
        return 'dashboard-total-agent';
    }
}
