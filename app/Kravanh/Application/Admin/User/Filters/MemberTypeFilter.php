<?php

namespace App\Kravanh\Application\Admin\User\Filters;

use App\Kravanh\Domain\User\Models\MemberType;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class MemberTypeFilter extends Filter
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
        return MemberType::select(['id', 'name'])->get()->map(fn($type) => [
            $type->name => $type->id
        ])->collapse()->toArray();
    }
}
