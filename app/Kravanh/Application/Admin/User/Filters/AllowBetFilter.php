<?php

namespace App\Kravanh\Application\Admin\User\Filters;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class AllowBetFilter extends Filter
{
    public $name = 'Allow Bet';
    /**
     * The filter's component.
     *
     * @var string
     */
    public $component = 'select-filter';

    /**
     * Apply the filter to the given query.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param mixed $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Request $request, $query, $value)
    {
        if (is_null($value)) {
            return $query;
        }

        return $query->where('can_bet_when_disable', $value);
    }

    /**
     * Get the filter's available options.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function options(Request $request): array
    {
        return [
            'Allowed' => 1,
            'Disabled' => 0
        ];
    }
}
