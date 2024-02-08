<?php

namespace App\Kravanh\Application\Admin\User\Filters;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class AgentMemberTypeFilter extends Filter
{
    public $name = 'User Type';
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

        if ($value === 'vip') {
            return $query->where('current_team_id', '>', 1);
        }

        return $query->where('current_team_id', $value);
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
            'Normal' => 1,
            'VIP' => 'vip'
        ];
    }
}
