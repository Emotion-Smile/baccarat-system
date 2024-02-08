<?php

namespace App\Kravanh\Application\Admin\User;

use App\Kravanh\Application\Admin\User\Actions\DownlineDepositNovaAction;
use App\Kravanh\Application\Admin\User\Actions\DownlineWithdrawNovaAction;
use App\Kravanh\Application\Admin\User\Actions\ForceUpdateBetStatusMemberFromUplineNovaAction;
use App\Kravanh\Application\Admin\User\Actions\ForceUpdateMatchConditionFromUpLineNovaAction;
use App\Kravanh\Application\Admin\User\Supports\Traits\UserFields;
use App\Kravanh\Domain\DragonTiger\App\Nova\Supports\NovaResourceHasDragonTiger;
use App\Kravanh\Domain\User\Models\Senior as SeniorModel;
use App\Kravanh\Domain\User\Models\SuperSenior;
use App\Kravanh\Domain\User\Supports\Enums\UserType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Http\Requests\NovaRequest;

// use App\Kravanh\Domain\Integration\Nova\Actions\T88GameConditionNovaAction;

class Senior extends UserResourceGroup
{
    use UserFields;
    use NovaResourceHasDragonTiger;

    public static $priority = 3;

    public static $model = SeniorModel::class;

    public static $title = 'Senior';

    public static $search = ['name'];

    public function fields(Request $request): array
    {

        return [
            // $this->dragonTigerInlineButton(),
            // $this->t88GameConditionActionButton(
            //     condition: $request->user()->isSuperSenior() && $request->user()->hasAllowT88Game() && $request->user()->hasT88GameCondition('LOTTO-12')
            // ),
            $this->dragonTigerGameConditionField(
                condition: $this->allowDragonTiger(
                    canSee: $request->user()->isSuperSenior()
                )
            ),
            $this->t88GameConditionField(
                condition: $request->user()->isSuperSenior() && $request->user()->hasAllowT88Game() && $request->user()->hasT88GameCondition('LOTTO-12')
            ),
            ...$this->userInfo(
                userType: Hidden::make('type')->default(UserType::SENIOR)
            ),
            ...$this->downlineCondition([
                ...$this->downlineShare(),
                ...$this->matchCondition()
            ]),
            ...$this->suspend(),
            ...$this->userTransactionInfo(),
            ...$this->depositAndWithdrawButton(UserType::SUPER_SENIOR)
        ];

    }

    public static function indexQuery(NovaRequest $request, $query): Builder
    {
        parent::indexQuery($request, $query);
        return $query->where('type', UserType::SENIOR);
    }


    public function actions(Request $request): array
    {

        //@TODO please don't use if condition to check the actions should visible or not
        $user = SuperSenior::find($request->user()->id);

        return [
            (new DownlineWithdrawNovaAction($user))
                ->onlyOnDetail()
                ->canSee(function ($request) {
                    return $request->user()->type->is(UserType::SUPER_SENIOR);
                })->canRun(function ($request) {
                    return $request->user()->type->is(UserType::SUPER_SENIOR);
                }),
            (new DownlineDepositNovaAction($user))
                ->onlyOnDetail()
                ->canSee(function ($request) {
                    return $request->user()->type->is(UserType::SUPER_SENIOR);
                })->canRun(function ($request) {
                    return $request->user()->type->is(UserType::SUPER_SENIOR);
                }),

            (new ForceUpdateMatchConditionFromUpLineNovaAction)
                ->onlyOnDetail()
                ->canSee(function ($request) {
                    return $request->user()->type->is(UserType::COMPANY);
                })->canRun(function ($request) {
                    return $request->user()->type->is(UserType::COMPANY);
                }),

            (new ForceUpdateBetStatusMemberFromUplineNovaAction)
                ->canRun(fn() => user()->type->is(UserType::SUPER_SENIOR) || user()->isCompany())
                ->onlyOnTableRow(),

            // T88GameConditionNovaAction::make($this->model())
            //     ->canSee(fn($request) => $request->user()->isSuperSenior() && $request->user()->hasAllowT88Game() && $request->user()->hasT88GameCondition('LOTTO-12'))
            //     ->canRun(fn($request) => $request->user()->isSuperSenior() && $request->user()->hasAllowT88Game() && $request->user()->hasT88GameCondition('LOTTO-12')),

            // $this->dragonTigerActionButton($request),
        ];
    }

    public static function authorizable(): bool
    {
        return true;
    }

    protected function getUserType(): string
    {
        return UserType::SENIOR;
    }
}
