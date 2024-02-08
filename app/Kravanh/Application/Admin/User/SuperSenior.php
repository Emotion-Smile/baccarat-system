<?php

namespace App\Kravanh\Application\Admin\User;

use App\Kravanh\Application\Admin\Environment\Domain;
use App\Kravanh\Application\Admin\Environment\Environment;
use App\Kravanh\Application\Admin\User\Actions\CompanyDepositToSuperSeniorNovaAction;
use App\Kravanh\Application\Admin\User\Actions\CompanyWithdrawFromSuperSeniorNovaAction;
use App\Kravanh\Application\Admin\User\Actions\ForceUpdateBetStatusMemberFromUplineNovaAction;
use App\Kravanh\Application\Admin\User\Actions\UseSecondTraderNovaAction;
use App\Kravanh\Application\Admin\User\Supports\Traits\UserFields;
// use App\Kravanh\Domain\DragonTiger\App\Nova\Actions\DragonTigerBetConditionNovaAction;
use App\Kravanh\Domain\DragonTiger\DragonTigerSetting;
// use App\Kravanh\Domain\Integration\Nova\Actions\T88GameConditionNovaAction;
use App\Kravanh\Domain\User\Models\SuperSenior as SuperSeniorModel;
use App\Kravanh\Domain\User\Supports\Enums\UserType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;

class SuperSenior extends UserResourceGroup
{
    use UserFields;

    public static $priority = 2;

    public static $model = SuperSeniorModel::class;

    public static $title = '';

    public static $search = [];


    public static function indexQuery(NovaRequest $request, $query): Builder
    {
        parent::indexQuery($request, $query);
        //@TODO optimize query by index
        return $query->where('type', UserType::SUPER_SENIOR);
    }


    public function fields(Request $request): array
    {
        $fields = [
            // $this->t88GameConditionActionButton(
            //     condition: $request->user()->isCompany()
            // ),

            $this->t88GameConditionField(
                condition: $request->user()->isCompany()
            ),

            BelongsTo::make(
                'Environment',
                'environment',
                Environment::class
            )
                ->default(1)
                ->showOnUpdating(false),

            BelongsTo::make('Domain', 'domain', Domain::class)
                ->nullable(),

            Boolean::make('Allow AF88 Games', 'allow_af88_game'),

            Boolean::make('Allow T88 Games', 'allow_t88_game'),

            ...$this->userInfo(
                userType: Hidden::make('type')->default(UserType::SUPER_SENIOR)
            ),
            ...$this->downlineCondition([
                ...$this->downlineShare(),
                ...$this->matchCondition()
            ]),
            ...$this->suspend(),
            ...$this->makeSelectCurrency(),
            ...$this->userTransactionInfo(),
            ...$this->makeDepositDrawButton($request)
        ];

        return $this->makeDragonTigerButton($fields);
    }


    public function actions(Request $request): array
    {
        $actions = [
            CompanyWithdrawFromSuperSeniorNovaAction::build($this->model()),
            CompanyDepositToSuperSeniorNovaAction::build($this->model()),
            ForceUpdateBetStatusMemberFromUplineNovaAction::build(),
            UseSecondTraderNovaAction::make($this->resource?->id ?? 0),
            // T88GameConditionNovaAction::make($this->model())
            //     ->canSee(fn($request) => $request->user()->isCompany())
            //     ->canRun(fn($request) => $request->user()->isCompany()),
        ];

        // if (DragonTigerSetting::allow()) {
        //     $actions[] = DragonTigerBetConditionNovaAction::make($this->model());
        // }

        return $actions;
    }


    private function makeDepositDrawButton(Request $request): array
    {
        if (!$request->user()->isCompany()) {
            return [];
        }

        $redColor = '#f25d56';

        return [
            CompanyDepositToSuperSeniorNovaAction::toInlineButton(
                label: 'Deposit',
                resourceId: $this->id,
                resource: $this->model()
            ),
            CompanyWithdrawFromSuperSeniorNovaAction::toInlineButton(
                label: 'Withdraw',
                resourceId: $this->id,
                resource: $this->model()
            )->buttonColor($redColor)
        ];
    }

    private function makeDragonTigerButton($fields)
    {
        if (DragonTigerSetting::allow()) {

            array_unshift(
                $fields,
                // DragonTigerBetConditionNovaAction::toInlineButton(
                //     label: 'D&T',
                //     resourceId: $this->id,
                //     resource: $this->model()
                // )
                $this->dragonTigerGameConditionField(
                    condition: true
                )
            );

        }

        return $fields;
    }

    private function makeSelectCurrency(): array
    {
        return [
            Select::make('Currency')->options([
                'KHR' => 'KHR',
                'USD' => 'USD',
                'THB' => 'THB',
                'VND' => 'VND'
            ])
                ->rules('required')
                ->hideWhenUpdating()
        ];
    }

    protected function getUserType(): string
    {
        return UserType::SUPER_SENIOR;
    }

}
