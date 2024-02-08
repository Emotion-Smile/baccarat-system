<?php

namespace App\Kravanh\Application\Admin\User\Filters;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class PasswordFilter extends Filter
{
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
        if ($value !== 'password') {
            return $query;
        }

        return $query->groupBy('password');
    }

    /**
     * Get the filter's available options.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function options(Request $request)
    {
        return [
            'Group By Password' => 'password'
        ];
    }
}
