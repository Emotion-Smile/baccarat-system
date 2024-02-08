<?php

namespace App\Kravanh\Application\Admin\User;

use App\Kravanh\Application\Admin\User\Actions\DownlineDepositNovaAction;
use App\Kravanh\Application\Admin\User\Actions\DownlineWithdrawNovaAction;
use App\Kravanh\Application\Admin\User\Actions\ForceUpdateBetStatusMemberFromUplineNovaAction;
use App\Kravanh\Application\Admin\User\Actions\ForceUpdateMatchConditionFromUpLineNovaAction;
use App\Kravanh\Application\Admin\User\Supports\Traits\UserFields;
use App\Kravanh\Domain\DragonTiger\App\Nova\Supports\NovaResourceHasDragonTiger;
use App\Kravanh\Domain\User\Models\MasterAgent as MasterAgentModel;
use App\Kravanh\Domain\User\Models\Senior;
use App\Kravanh\Domain\User\Supports\Enums\UserType;
use Illuminate\Http\Request;
use Kravanh\BetCondition\BetCondition;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Http\Requests\NovaRequest;

// use App\Kravanh\Domain\Integration\Nova\Actions\T88GameConditionNovaAction;

class MasterAgent extends UserResourceGroup
{
    use UserFields;
    use NovaResourceHasDragonTiger;

    public static $priority = 4;

    public static $model = MasterAgentModel::class;

    public static $title = 'name';

    public static $search = ['name', 'phone'];

    public function fields(Request $request): array
    {
        return [
            // $this->dragonTigerInlineButton(),
            // $this->t88GameConditionActionButton(
            //     condition: $request->user()->isSenior() && $request->user()->hasAllowT88Game() && $request->user()->hasT88GameCondition('LOTTO-12')
            // ),
            $this->dragonTigerGameConditionField(
                condition: $this->allowDragonTiger(
                    canSee: $request->user()->isSenior()
                )
            ),
            $this->t88GameConditionField(
                condition: $request->user()->isSenior() && $request->user()->hasAllowT88Game() && $request->user()->hasT88GameCondition('LOTTO-12')
            ),
            BetCondition::make('Bet Condition')
                ->user($this)
                ->actionUser($request->user())
                ->onlyOnIndex(),
            ...$this->userInfo(
                userType: Hidden::make('type')->default(UserType::MASTER_AGENT)
            ),
            ...$this->downlineCondition([
                ...$this->downlineShare(),
                ...$this->matchCondition()
            ]),
            ...$this->suspend(),
            ...$this->userTransactionInfo(),
            ...$this->depositAndWithdrawButton(UserType::SENIOR)
        ];
    }

    public static function indexQuery(NovaRequest $request, $query)
    {
        parent::indexQuery($request, $query);
        return $query->where('type', UserType::MASTER_AGENT);
    }

    public function actions(Request $request): array
    {
        $user = Senior::find($request->user()->id);

        return [
            (new DownlineWithdrawNovaAction($user))
                ->onlyOnDetail()
                ->canSee(function ($request) {
                    return $request->user()->type->is(UserType::SENIOR);
                })->canRun(function ($request) {
                    return $request->user()->type->is(UserType::SENIOR);
                }),
            (new DownlineDepositNovaAction($user))
                ->onlyOnDetail()
                ->canSee(function ($request) {
                    return $request->user()->type->is(UserType::SENIOR);
                })->canRun(function ($request) {
                    return $request->user()->type->is(UserType::SENIOR);
                }),

            (new ForceUpdateMatchConditionFromUpLineNovaAction)
                ->onlyOnDetail()
                ->canSee(function ($request) {
                    return $request->user()->type->is(UserType::COMPANY);
                })->canRun(function ($request) {
                    return $request->user()->type->is(UserType::COMPANY);
                }),

            (new ForceUpdateBetStatusMemberFromUplineNovaAction)
                ->canRun(fn() => user()->type->is(UserType::SENIOR) || user()->isCompany())
                ->onlyOnTableRow(),

            // T88GameConditionNovaAction::make($this->model())
            //     ->canSee(fn($request) => $request->user()->isSenior() && $request->user()->hasAllowT88Game() && $request->user()->hasT88GameCondition('LOTTO-12'))
            //     ->canRun(fn($request) => $request->user()->isSenior() && $request->user()->hasAllowT88Game() && $request->user()->hasT88GameCondition('LOTTO-12')),

            // $this->dragonTigerActionButton($request),
        ];
    }

    protected function getUserType()
    {
        return UserType::MASTER_AGENT;
    }
}
