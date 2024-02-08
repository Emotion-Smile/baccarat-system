<?php

namespace App\Kravanh\Application\Admin\Match;

use App\Kravanh\Application\Admin\Match\Actions\DepositMissingPayoutNovaAction;
use App\Kravanh\Application\Admin\Match\Actions\UpdatePendingResultNovaAction;
use App\Kravanh\Domain\Match\Models\Matches;
use App\Kravanh\Domain\Match\Supports\Enums\MatchResult;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Nova\Http\Requests\NovaRequest;

class PendingMatch extends MatchResourceGroup
{
    use MatchField;

    public static $model = Matches::class;

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
        return $query->where('result', MatchResult::PENDING);
    }

    public function actions(Request $request): array
    {
        return [
            (new UpdatePendingResultNovaAction())
                ->canRun(fn() => Auth::user()->can('update-pending-result', Matches::class)),
            (new DepositMissingPayoutNovaAction())->canRun(fn() => user()->isCompany())
        ];
    }
}
