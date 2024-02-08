<?php


namespace App\Kravanh\Application\Admin\User;

use App\Kravanh\Application\Admin\Match\BetRecord;
use App\Kravanh\Application\Admin\Match\Spread;
use App\Kravanh\Application\Admin\User\Actions\AllowVideoStreamingAction;
use App\Kravanh\Application\Admin\User\Actions\DisableTVNovaAction;
use App\Kravanh\Application\Admin\User\Actions\DownlineDepositNovaAction;
use App\Kravanh\Application\Admin\User\Actions\DownlineWithdrawNovaAction;
use App\Kravanh\Application\Admin\User\Actions\RefreshPageNovaAction;
use App\Kravanh\Application\Admin\User\Filters\AgentMemberTypeFilter;
use App\Kravanh\Application\Admin\User\Filters\AllowBetFilter;
use App\Kravanh\Application\Admin\User\Filters\DisableTVFilter;
use App\Kravanh\Application\Admin\User\Filters\MemberTypeFilter;
use App\Kravanh\Application\Admin\User\Filters\SpreadFilter;
use App\Kravanh\Application\Admin\User\Filters\SuspendFilter;
use App\Kravanh\Application\Admin\User\Supports\Traits\UserFields;
use App\Kravanh\Domain\DragonTiger\App\Nova\Supports\NovaResourceHasDragonTiger;
use App\Kravanh\Domain\Integration\Nova\Actions\AF88GameConditionNovaAction;
use App\Kravanh\Domain\User\Models\Agent;
use App\Kravanh\Domain\User\Models\Member as MemberModel;
use App\Kravanh\Domain\User\Supports\Enums\UserType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Kravanh\BetCondition\BetCondition;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\MorphMany;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Spatie\TagsField\Tags;

// use App\Kravanh\Domain\Integration\Nova\Actions\T88GameConditionNovaAction;

class Member extends UserResourceGroup
{
    use UserFields;
    use NovaResourceHasDragonTiger;

    public static $priority = 5;

    public static $model = MemberModel::class;

    public static $title = 'name';

    public static $search = ['name', 'phone'];

    public function fields(Request $request): array
    {
        $currency = $this->currency;

        return [
            // $this->dragonTigerInlineButton(),
            BetCondition::make('Bet Condition')
                ->user($this)
                ->actionUser($request->user())
                ->onlyOnIndex(),

            $this->dragonTigerGameConditionField(
                condition: $this->allowDragonTiger(
                    canSee: $request->user()->isAgent()
                )
            ),

            $this->af88GameConditionActionButton(
                condition: $request->user()->isAgent() && $request->user()->hasAllowAF88Game() && $request->user()->hasAF88GameCondition()
            ),

            // $this->t88GameConditionActionButton(
            //     condition: $request->user()->isAgent() && $request->user()->hasAllowT88Game() && $request->user()->hasT88GameCondition('LOTTO-12')
            // ),

            $this->t88GameConditionField(
                condition: $request->user()->isAgent() && $request->user()->hasAllowT88Game() && $request->user()->hasT88GameCondition('LOTTO-12')
            ),

            ...$this->userInfo(
                userType: Hidden::make('type')->default(UserType::MEMBER)
            ),

            ...$this->downlineCondition([
                ...$this->winLimitPerDay(),
                ...$this->matchCondition(),
            ]),

            ...$this->accountStatus(),
            Boolean::make('Allow Stream')
                ->default(false)
                ->canSee(function (Request $request) {
                    return ($request->user()->isRoot() || $request->user()->hasPermission('Member:allow-stream'));
                }),
            Boolean::make('Allow bet when disabled', 'can_bet_when_disable')->canSee(fn(Request $request) => $request->user()->isCompany()),
            BelongsTo::make(
                'Type',
                'memberType',
                MemberType::class
            )->nullable(),
            Select::make('Type', 'current_team_id')->options(function () {

                $vipId = $this->current_team_id;

                if (is_null($this->current_team_id) || $this->current_team_id === 1) {
                    $vipId = 2;
                }

                return [
                    1 => 'Normal',
                    $vipId => 'VIP',
                ];
            })->canSee(fn() => !$request->user()->isCompany())
                ->displayUsingLabels()
                ->nullable(),
            BelongsTo::make(
                'Group',
                'spread',
                Spread::class
            )->nullable(),
            Tags::make('Tags')->canSee(fn(Request $request) => $request->user()->isCompany()),
            Currency::make('Balance', 'current_balance')
                ->displayUsing(function ($currentBalance) use ($currency) {
                    return priceFormat(fromKHRtoCurrency($currentBalance, $currency), $currency->key);
                })
                ->sortable()
                ->withMeta(['sortableUriKey' => 'wallets.balance'])
                ->onlyOnIndex(),

            MorphMany::make('Transactions'),
            ...$this->depositAndWithdrawButton(UserType::AGENT),
            Text::make('', function () {
                return view('partials.shortcut-member-win-lose', [
                    'memberId' => $this->id
                ])->render();
            })->asHtml(),
            HasMany::make('Bets', 'bets', BetRecord::class)->canSee(fn() => $request->user()->isCompany()),
            Boolean::make("Disable TV")->resolveUsing(function () {
                return $this->isUserPresentInDisableGroup($this->id, $this->agent);
            })->onlyOnIndex(),

        ];
    }

    private function isUserPresentInDisableGroup(int $userId, int $agentId): bool
    {
        $result = DB::table('group_user')
            ->select('user_id')
            ->whereIn('user_id', [$userId, $agentId])
            ->first();

        if (is_null($result)) {
            return false;
        }

        return true;
    }

    public static function indexQuery(NovaRequest $request, $query)
    {
        parent::indexQuery($request, $query);
        return $query
            ->leftJoin('wallets', 'users.id', '=', 'wallets.holder_id')
            ->select('users.*', 'wallets.balance as current_balance')
            ->where('users.type', UserType::MEMBER);
    }

    public function actions(Request $request): array
    {

        $user = Agent::find($request->user()->id);

        return [
            (new DownlineWithdrawNovaAction($user))
                ->onlyOnDetail()
                ->canSee(function ($request) {
                    return $request->user()->type->is(UserType::AGENT) || $request->user()->hasPermission('Member:direct-withdraw');
                })->canRun(function ($request) {
                    return $request->user()->type->is(UserType::AGENT) || $request->user()->hasPermission('Member:direct-withdraw');
                }),
            (new DownlineDepositNovaAction($user))
                ->onlyOnDetail()
                ->canSee(function ($request) {
                    return $request->user()->type->is(UserType::AGENT) || $request->user()->hasPermission('Member:direct-deposit');
                })->canRun(function ($request) {
                    return $request->user()->type->is(UserType::AGENT) || $request->user()->hasPermission('Member:direct-deposit');
                }),
            (new AllowVideoStreamingAction($this->model()['allow_stream'] ?? 0))
                ->canRun(fn() => allowIf('Member:allow-stream')),
            RefreshPageNovaAction::make()->canSee(fn() => $request->user()->isCompany()),
            DisableTVNovaAction::make()
                ->onlyOnTableRow()
                ->setResourceId($this->resource?->id)
                ->canSee(fn($request) => $request->user()->type->is(UserType::AGENT) || $request->user()->isCompany())
                ->canRun(fn($request) => $request->user()->type->is(UserType::AGENT) || $request->user()->isCompany()),

            AF88GameConditionNovaAction::make($this->model())
                ->canSee(fn($request) => $request->user()->type->is(UserType::AGENT) && $request->user()->hasAllowAF88Game())
                ->canRun(fn($request) => $request->user()->type->is(UserType::AGENT) && $request->user()->hasAllowAF88Game()),

            // T88GameConditionNovaAction::make($this->model())
            //     ->canSee(fn($request) => $request->user()->type->is(UserType::AGENT) && $request->user()->underSuperSenior->hasAllowT88Game())
            //     ->canRun(fn($request) => $request->user()->type->is(UserType::AGENT) && $request->user()->underSuperSenior->hasAllowT88Game()),

            // $this->dragonTigerActionButton($request)
        ];
    }

    public function filters(Request $request)
    {
        return [
            AgentMemberTypeFilter::make()->canSee(fn() => !$request->user()->isCompany()),
            MemberTypeFilter::make()->canSee(fn() => $request->user()->isCompany()),
            SpreadFilter::make()->canSee(fn() => $request->user()->isCompany()),
            AllowBetFilter::make()->canSee(fn() => $request->user()->isCompany()),
            SuspendFilter::make(),
            DisableTVFilter::make()
        ];
    }
}
