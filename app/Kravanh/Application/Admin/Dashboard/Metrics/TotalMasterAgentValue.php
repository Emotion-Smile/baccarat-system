<?php

namespace App\Kravanh\Application\Admin\Dashboard\Metrics;

use App\Kravanh\Domain\User\Supports\Enums\UserType;
use App\Models\User;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Value;
use Laravel\Nova\Metrics\ValueResult;

class TotalMasterAgentValue extends Value
{

    public $name = 'Master Agent';

    public function calculate(NovaRequest $request): mixed
    {
        return new ValueResult(User::where('type', UserType::MASTER_AGENT)->active()->count());
    }


    public function ranges(): array
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
        return 'dashboard-total-master-agent';
    }
}
