<?php

namespace App\Kravanh\Application\Admin\Match;

use App\Kravanh\Application\Admin\Match\Actions\DepositMissingPayoutNovaAction;
use App\Kravanh\Application\Admin\Match\Actions\ModifyFightNumberNovaAction;
use App\Kravanh\Application\Admin\Match\Actions\ModifyResultNovaAction;
use App\Kravanh\Application\Admin\Match\Filters\DateRange;
use App\Kravanh\Domain\Match\Models\Matches as MatchesModel;
use App\Kravanh\Domain\Match\Supports\Enums\MatchResult;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Nova\Http\Requests\NovaRequest;

class Matches extends MatchResourceGroup
{
    use MatchField;

    public static $model = MatchesModel::class;

    public static $title = 'fight_number';

    public static $globallySearchable = false;

    public static $search = [
        'id',
        'fight_number',
    ];


    public function fields(Request $request): array
    {
        return $this->buildForm();
    }

    public static function indexQuery(NovaRequest $request, $query): Builder
    {
        return $query
            ->where('result', '!=', MatchResult::PENDING)
//            ->whereNotNull('match_end_at')
            ->orderByDesc('id');
    }

    public function actions(Request $request): array
    {
        return [
            (new ModifyResultNovaAction($this->resource->result ?? MatchResult::fromValue(0)))
                ->canRun(fn() => Auth::user()->can('update-pending-result', MatchesModel::class)),
            (new ModifyFightNumberNovaAction())
                ->canRun(fn() => Auth::user()->can('update-pending-result', MatchesModel::class)),
            (new DepositMissingPayoutNovaAction())->canRun(fn() => user()->isCompany()),
        ];
    }

    public function filters(Request $request)
    {
        return [
            DateRange::make('match_date'),
        ];
    }

}
