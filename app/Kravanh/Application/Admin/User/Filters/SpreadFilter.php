<?php

namespace App\Kravanh\Application\Admin\User\Filters;

use App\Kravanh\Domain\Match\Models\Spread;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class SpreadFilter extends Filter
{
    public $name = 'Group';
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

        return $query->where('spread_id', $value);
    }

    /**
     * Get the filter's available options.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function options(Request $request): array
    {
        return Spread::select(['id', 'name'])->get()->map(fn($spread) => [
            $spread->name => $spread->id
        ])->collapse()->toArray();

    }
}
