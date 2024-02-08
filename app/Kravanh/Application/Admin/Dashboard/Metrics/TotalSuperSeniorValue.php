<?php

namespace App\Kravanh\Application\Admin\Dashboard\Metrics;

use App\Kravanh\Domain\User\Supports\Enums\UserType;
use App\Models\User;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Value;
use Laravel\Nova\Metrics\ValueResult;

class TotalSuperSeniorValue extends Value
{

    public $name = 'Supper Senior';

    public function calculate(NovaRequest $request): mixed
    {
        return new ValueResult(
            User::where('type', UserType::SUPER_SENIOR)->active()->count()
        );
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
        return 'dashboard-total-super-senior';
    }
}
