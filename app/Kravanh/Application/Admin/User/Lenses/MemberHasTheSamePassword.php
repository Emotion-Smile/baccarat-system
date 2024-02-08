<?php

namespace App\Kravanh\Application\Admin\User\Lenses;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Http\Requests\LensRequest;
use Laravel\Nova\Lenses\Lens;

class MemberHasTheSamePassword extends Lens
{

    /**
     * Get the query builder / paginator for the lens.
     *
     * @param \Laravel\Nova\Http\Requests\LensRequest $request
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return mixed
     */
    public static function query(LensRequest $request, $query)
    {
        return $request->withOrdering($request->withFilters(
            $query
                ->select(self::columns())
                ->groupBy('password')
                ->having('total', '>', 1)
        ));
    }

    /**
     * Get the fields available to the lens.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make(__('ID'), function () {
                return User::query()
                    ->select('name')
                    ->whereIn('id', explode(',', $this->userId))
                    ->get()
                    ->map(fn($user) => $user->name)
                    ->join(',');
            }),
            Number::make('Total'),
            Number::make('Password', 'password')->resolveUsing(function ($password) {
                return substr($password, 0, strlen($password) - 3) . '...';
            })
        ];
    }

    /**
     * Get the columns that should be selected.
     *
     * @return array
     */
    protected static function columns(): array
    {
        return [
            DB::raw('GROUP_CONCAT(user_id) as userId'),
            DB::raw('count(*) as total'),
            'password'
        ];
    }


    /**
     * Get the cards available on the lens.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the lens.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available on the lens.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function actions(Request $request)
    {
        return parent::actions($request);
    }

    /**
     * Get the URI key for the lens.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'member-has-the-same-password';
    }
}
