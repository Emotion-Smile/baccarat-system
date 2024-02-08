<?php


namespace App\Kravanh\Application\Admin\User;

use App\Kravanh\Application\Admin\Match\Spread;
use App\Kravanh\Application\Admin\User\Actions\DisableTVNovaAction;
use App\Kravanh\Application\Admin\User\Actions\DownlineDepositNovaAction;
use App\Kravanh\Application\Admin\User\Actions\DownlineWithdrawNovaAction;
use App\Kravanh\Application\Admin\User\Actions\ForceUpdateBetStatusMemberFromUplineNovaAction;
use App\Kravanh\Application\Admin\User\Actions\ForceUpdateMatchConditionFromUpLineNovaAction;
use App\Kravanh\Application\Admin\User\Filters\AllowBetFilter;
use App\Kravanh\Application\Admin\User\Filters\DisableTVFilter;
use App\Kravanh\Application\Admin\User\Filters\MemberTypeFilter;
use App\Kravanh\Application\Admin\User\Filters\SpreadFilter;
use App\Kravanh\Application\Admin\User\Filters\SuspendFilter;
use App\Kravanh\Application\Admin\User\Supports\Traits\UserFields;
use App\Kravanh\Domain\DragonTiger\App\Nova\Supports\NovaResourceHasDragonTiger;
use App\Kravanh\Domain\Integration\Nova\Actions\AF88GameConditionNovaAction;
use App\Kravanh\Domain\User\Models\Agent as AgentModel;
use App\Kravanh\Domain\User\Models\MasterAgent;
use App\Kravanh\Domain\User\Supports\Enums\UserType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Kravanh\BetCondition\BetCondition;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Agent extends UserResourceGroup
{
    use UserFields;
    use NovaResourceHasDragonTiger;

    public static $priority = 5;

    public static $model = AgentModel::class;

    public static $title = 'name';

    public static $search = ['name', 'phone'];

    public function fields(Request $request): array
    {
        return [
            // $this->dragonTigerInlineButton(),
            BetCondition::make('Bet Condition')
                ->user($this)
                ->actionUser($request->user())
                ->onlyOnIndex(),
            $this->dragonTigerGameConditionField(
                condition: $this->allowDragonTiger(
                    canSee: $request->user()->isMasterAgent()
                )
            ),
            $this->af88GameConditionActionButton(
                condition: $request->user()->type->is(UserType::MASTER_AGENT) && $request->user()->hasAllowAF88Game()
            ),

            // $this->t88GameConditionActionButton(
            //     condition: $request->user()->isMasterAgent() && $request->user()->hasAllowT88Game()
            // ),

            $this->t88GameConditionField(
                condition: $request->user()->isMasterAgent() && $request->user()->hasAllowT88Game() && $request->user()->hasT88GameCondition('LOTTO-12')
            ),

            ...$this->userInfo(
                userType: Hidden::make('type')->default(UserType::AGENT)
            ),
            ...$this->downlineCondition([
                ...$this->downlineShare(),
                ...$this->matchCondition()
            ]),
            ...$this->suspend(),
            ...$this->userTransactionInfo(),
            BelongsTo::make(
                'Type',
                'memberType',
                MemberType::class
            )->nullable(),
            BelongsTo::make(
                'Group',
                'spread',
                Spread::class
            )->nullable(),
            Boolean::make('Allow bet when disabled', 'can_bet_when_disable')->canSee(fn(Request $request) => $request->user()->isCompany()),
            ...$this->depositAndWithdrawButton(UserType::MASTER_AGENT),
            // AttachMany::make('Disable TV', 'disableGroups', Group::class),
            Boolean::make('Disable TV')->resolveUsing(function () {
                return $this->isUserPresentInDisableGroup($this->id);
            })->onlyOnIndex(),

            Text::make('List TV disabled')->resolveUsing(function () {

                return $this->getListTVDisabled($this);
            })->onlyOnDetail(),

        ];
    }

    private function getListTVDisabled($resource): string
    {
        /**
         * @var \App\Models\User $resource
         */

        $groupName = $resource->disableGroups()
            ->select('name')
            ->get()
            ->map(fn($item) => $item->name)
            ->toArray();

        return implode(',', $groupName);
    }

    public static function indexQuery(NovaRequest $request, $query)
    {
        parent::indexQuery($request, $query);


        return $query->where('type', UserType::AGENT);
    }

    public function actions(Request $request): array
    {
        $user = MasterAgent::find($request->user()->id);

        return [
            (new DownlineWithdrawNovaAction($user))
                ->onlyOnDetail()
                ->canSee(function ($request) {
                    return $request->user()->type->is(UserType::MASTER_AGENT) || $request->user()->hasPermission('Member:direct-withdraw');
                })->canRun(function ($request) {
                    return $request->user()->type->is(UserType::MASTER_AGENT) || $request->user()->hasPermission('Member:direct-withdraw');
                }),
            (new DownlineDepositNovaAction($user))
                ->onlyOnDetail()
                ->canSee(function ($request) {
                    return $request->user()->type->is(UserType::MASTER_AGENT) || $request->user()->hasPermission('Member:direct-deposit');
                })->canRun(function ($request) {
                    return $request->user()->type->is(UserType::MASTER_AGENT) || $request->user()->hasPermission('Member:direct-deposit');
                }),

            (new ForceUpdateMatchConditionFromUpLineNovaAction)
                ->onlyOnDetail()
                ->canSee(function ($request) {
                    return $request->user()->type->is(UserType::COMPANY);
                })->canRun(function ($request) {
                    return $request->user()->type->is(UserType::COMPANY);
                }),

            (new ForceUpdateBetStatusMemberFromUplineNovaAction)
                ->canRun(fn() => user()->type->is(UserType::MASTER_AGENT) || user()->isCompany())
                ->onlyOnTableRow(),

            DisableTVNovaAction::make()
                ->onlyOnTableRow()
                ->setResourceId($this->resource?->id)
                ->canSee(fn($request) => $request->user()->type->is(UserType::MASTER_AGENT) || $request->user()->isCompany())
                ->canRun(fn($request) => $request->user()->type->is(UserType::MASTER_AGENT) || $request->user()->isCompany()),

            AF88GameConditionNovaAction::make($this->model())
                ->canSee(fn($request) => $request->user()->type->is(UserType::MASTER_AGENT) && $request->user()->hasAllowAF88Game())
                ->canRun(fn($request) => $request->user()->type->is(UserType::MASTER_AGENT) && $request->user()->hasAllowAF88Game()),

            // T88GameConditionNovaAction::make($this->model())
            //     ->canSee(fn($request) => $request->user()->type->is(UserType::MASTER_AGENT) && $request->user()->underSuperSenior->hasAllowT88Game())
            //     ->canRun(fn($request) => $request->user()->type->is(UserType::MASTER_AGENT) && $request->user()->underSuperSenior->hasAllowT88Game()),
            // $this->dragonTigerActionButton($request)
        ];
    }

    private function isUserPresentInDisableGroup(int $userId): bool
    {
        $result = DB::table('group_user')
            ->select('user_id')
            ->where('user_id', $userId)
            ->first();

        if (is_null($result)) {
            return false;
        }

        return true;
    }

    protected function getUserType()
    {
        return UserType::AGENT;
    }

    public function filters(Request $request)
    {
        return [
            MemberTypeFilter::make(),
            SpreadFilter::make()->canSee(fn() => $request->user()->isCompany()),
            AllowBetFilter::make()->canSee(fn() => $request->user()->isCompany()),
            SuspendFilter::make(),
            DisableTVFilter::make()
        ];
    }
}
