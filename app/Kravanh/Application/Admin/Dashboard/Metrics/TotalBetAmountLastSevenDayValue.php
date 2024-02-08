<?php

namespace App\Kravanh\Application\Admin\Dashboard\Metrics;

use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Value;
use Laravel\Nova\Metrics\ValueResult;

class TotalBetAmountLastSevenDayValue extends Value
{

    public $name = 'Total';

    public function calculate(NovaRequest $request): mixed
    {
        return 0;
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
        return 'dashboard-total-bet-amount-last-seven-day';
    }
}
