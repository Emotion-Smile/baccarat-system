<?php

namespace App\Kravanh\Application\Admin\Dashboard\Metrics;

use App\Kravanh\Domain\User\Supports\Enums\UserType;
use App\Models\User;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Value;
use Laravel\Nova\Metrics\ValueResult;

class TotalMemberValue extends Value
{

    public $name = 'Member';

    public function calculate(NovaRequest $request): mixed
    {
        return new ValueResult(User::where('type', UserType::MEMBER)->active()->count());
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
        return 'dashboard-total-member';
    }
}
