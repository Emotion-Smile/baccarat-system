<?php

namespace App\Kravanh\Application\Admin\User\Supports\Traits;

use App\Kravanh\Application\Admin\User\Actions\DownlineDepositNovaAction;
use App\Kravanh\Application\Admin\User\Actions\DownlineWithdrawNovaAction;
use App\Kravanh\Application\Admin\User\Role;
use App\Kravanh\Domain\Integration\Nova\Actions\AF88GameConditionNovaAction;
use App\Kravanh\Domain\Integration\Nova\Actions\T88GameConditionNovaAction;
use App\Kravanh\Domain\User\Supports\Enums\Status;
use App\Kravanh\Domain\User\Supports\Enums\UserType;
use App\Models\User;
use Illuminate\Validation\Rules\Password as PasswordRule;
use KravanhEco\Balance\Balance;
use KravanhEco\GameCondition\GameCondition;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Heading;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\MorphMany;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Text;
use Naoray\NovaJson\JSON;
use NovaAttachMany\AttachMany;
use Pdmfc\NovaFields\ActionButton;
use SimpleSquid\Nova\Fields\Enum\Enum;

/**
 * @mixin User
 */
trait UserFields
{
    protected function userInfo(array $rolesFields = [], Hidden|Enum $userType = null): array
    {
        return [

            Heading::make('User Information'),

            ID::make(__('ID'), 'id')
                ->sortable(),

            $userType ?? Enum::make('Type')->attach(UserType::class),

            Text::make('Super Senior', fn() => $this->underSuperSenior?->name)
                ->hideFromIndex(!($this->super_senior && canCurrentUserAccessLevel(UserType::SENIOR)))
                ->hideFromDetail(!($this->super_senior && canCurrentUserAccessLevel(UserType::SENIOR))),

            Text::make('Senior', fn() => $this->underSenior?->name)
                ->hideFromIndex(!($this->senior && canCurrentUserAccessLevel(UserType::MASTER_AGENT)))
                ->hideFromDetail(!($this->senior && canCurrentUserAccessLevel(UserType::MASTER_AGENT))),

            Text::make('Master Agent', fn() => $this->underMasterAgent?->name)
                ->hideFromIndex(!($this->master_agent && canCurrentUserAccessLevel(UserType::AGENT)))
                ->hideFromDetail(!($this->master_agent && canCurrentUserAccessLevel(UserType::AGENT))),

            Text::make('Agent', fn() => $this->underAgent?->name)
                ->hideFromIndex(!($this->agent && canCurrentUserAccessLevel(UserType::MEMBER)))
                ->hideFromDetail(!($this->agent && canCurrentUserAccessLevel(UserType::MEMBER))),

            Text::make(__('Name'), 'name')
                ->sortable()
                ->creationRules(
                    'required',
                    'unique:users,name'
                )
                ->updateRules(
                    'required',
                    'unique:users,name,{{resourceId}}'
                )
                ->hideWhenUpdating(),


            Text::make(__('Name'), 'name')
                ->hideFromIndex()
                ->hideFromDetail()
                ->hideWhenCreating()
                ->readonly(),

            Password::make(__('Password'), 'password')
                ->onlyOnForms()
                ->creationRules(
                    'required',
                    $this->passwordRule()
                )
                ->updateRules(
                    'nullable',
                    $this->passwordRule()
                ),

            // Text::make(__('Phone'), 'phone')
            //     ->nullable()
            //     ->rules('nullable'),
            //->creationRules('unique:users,phone')
            //->updateRules('unique:users,phone,{{resourceId}}'),
//
//            Text::make(__('Email'), 'email')
//                ->nullable()
//                ->rules('nullable', 'email', 'max:254')
//                //->creationRules('unique:users,email')
//                //->updateRules('unique:users,email,{{resourceId}}')
//                ->hideFromIndex(),

            Text::make('Contact', 'phone')
                ->nullable()
                ->rules('nullable'),

            ...$rolesFields,

            HasMany::make('Login Histories', 'loginHistories')
        ];
    }

    protected function userTransactionInfo(): array
    {

        return [
            Text::make('Balance', function () {
                return priceFormat($this->getCurrentBalance() ?? 0, $this->currency->key);
            })
                ->onlyOnIndex()
                ->sortable(),
            MorphMany::make('Transactions')
        ];
    }

    protected function passwordRule(): PasswordRule
    {
        return PasswordRule::min(6);
        // return PasswordRule::min(8) // Require at least 8 characters
        //     ->mixedCase() // Require at least one letter
        //     ->letters() // Require at least one uppercase and one lowercase
        //     ->numbers() // Require at least one number
        //     ->symbols() // Require at least one symbol
        //     ->uncompromised(); // Must not compromised in data leaks - Ke2023@yeaR
    }

    protected function downlineCondition(array $matchCondition = []): array
    {
        return [
            Heading::make('Downline Condition')
                ->hideWhenUpdating($this->isCurrentUserTypeSameAsResoureUserType()),
            JSON::make('Condition', 'condition', [

                // Number::make('My position share', 'my_position_share')
                //     ->rules('required'),

                // Number::make('Down line share', 'down_line_share')
                //     ->rules('required'),

                // Number::make('Win Limit Per Day', 'credit_limit')
                //     ->rules('required')
                //     ->default(0)
                //     ->help('0 = unrestricted')
                //     ->hideFromIndex(),

                Number::make('Commission', 'commission')
                    ->rules('gte:0', 'lte:100')
                    ->step(0.01)
                    ->default(0)
                    ->hideFromIndex()
                    ->hideWhenUpdating($this->isCurrentUserTypeSameAsResoureUserType()),

                ...$matchCondition
            ])
        ];
    }

    protected function winLimitPerDay(): array
    {
        return [
            Balance::make('Win Limit Per Day', 'credit_limit')
                ->rules('required')
                ->default(0)
                ->help('0 = unrestricted')
                ->hideFromIndex()
                ->hideWhenUpdating($this->isCurrentUserTypeSameAsResoureUserType()),
        ];
    }

    protected function downlineShare(): array
    {
        $userDownLineShare = user()->condition['down_line_share'] ?? 100;

        return [
            Number::make('My position share', 'my_position_share')
                ->exceptOnForms()
                ->hideWhenUpdating($this->isCurrentUserTypeSameAsResoureUserType()),

            Number::make('Down line share', 'down_line_share')
                ->rules(
                    'required',
                    "lte:{$userDownLineShare}"
                )
                ->help("Current: {$userDownLineShare}%")
                ->hideWhenUpdating($this->isCurrentUserTypeSameAsResoureUserType()),
        ];
    }

    protected function matchCondition(): array
    {
        $minimumBetPerTicket = user()->condition['minimum_bet_per_ticket'] ?? null;
        $maximumBetPerTicket = user()->condition['maximum_bet_per_ticket'] ?? null;
        $matchLimit = user()->condition['match_limit'] ?? null;

        return [
            Balance::make('Minimum bet per ticket', 'minimum_bet_per_ticket')
                ->default($minimumBetPerTicket)
                ->rules(
                    'required',
                    'numeric',
                    $minimumBetPerTicket ? "gte:{$minimumBetPerTicket}" : null
                )
                ->hideFromIndex()
                ->hideWhenUpdating($this->isCurrentUserTypeSameAsResoureUserType()),

            Balance::make('Maximum bet per ticket', 'maximum_bet_per_ticket')
                ->default($maximumBetPerTicket)
                ->rules(
                    'required',
                    'numeric',
                    $minimumBetPerTicket ? "gt:{$minimumBetPerTicket}" : null,
                    $maximumBetPerTicket ? "lte:{$maximumBetPerTicket}" : null,
                )
                ->hideFromIndex()
                ->hideWhenUpdating($this->isCurrentUserTypeSameAsResoureUserType()),

            Balance::make('Match limit', 'match_limit')
                ->default($matchLimit)
                ->rules(
                    'required',
                    'numeric',
                    $matchLimit ? "lte:{$matchLimit}" : null
                )
                ->hideFromIndex()
                ->hideWhenUpdating($this->isCurrentUserTypeSameAsResoureUserType())
        ];
    }

    protected function depositAndWithdrawButton(string $userType): array
    {
        $model = getModelByUserType($userType);
        $user = $model::find(user()->id);

        return [
            ActionButton::make('')
                ->text('Deposit')
                ->action(new DownlineDepositNovaAction($user), $this->id)
                ->onlyOnIndex()
                ->showOnIndex(function ($request) use ($userType) {
                    return $request->user()->type->is($userType) || $request->user()->hasPermission('Member:direct-deposit');
                }),

            ActionButton::make('')
                ->text('Withdraw')
                ->buttonColor('#f25d56')
                ->action(new DownlineWithdrawNovaAction($user), $this->id)
                ->onlyOnIndex()
                ->showOnIndex(function ($request) use ($userType) {
                    return $request->user()->type->is($userType) || $request->user()->hasPermission('Member:direct-withdraw');
                }),
        ];
    }

    protected function t88GameConditionActionButton(bool $condition): ActionButton
    {
        $action = T88GameConditionNovaAction::make($this->model());

        return ActionButton::make('T88 Game Condition')
            ->text('T88')
            ->action($action, $this->id)
            ->onlyOnIndex()
            ->showOnIndex(fn() => $condition)
            ->readonly(fn() => count($action->fields()) <= 0);
    }

    protected function t88GameConditionField(bool $condition): GameCondition
    {
        return GameCondition::make('T88 Game Condition')
            ->text('T88')
            ->resource($this->id)
            ->fieldEndpoint(route('t88.get.game.condition.fields', ['user' => $this->id ?? 0]))
            ->executeEndpoint(route('t88.save.game.condition'))
            ->showOnIndex(fn() => $condition);
    }

    protected function dragonTigerGameConditionField(bool $condition): GameCondition
    {
        return GameCondition::make('D&T Game Condition')
            ->text('D&T')
            ->resource($this->id)
            ->fieldEndpoint(route('dragon-tiger.get.game.condition.fields', ['user' => $this->id ?? 0]))
            ->executeEndpoint(route('dragon-tiger.save.game.condition'))
            ->showOnIndex(fn() => $condition);
    }

    protected function accountStatus(): array
    {
        return [
            Heading::make('Status'),
            Boolean::make(
                __('Internet Betting'),
                'internet_betting'
            )
                ->default(true),

            Enum::make('Status')
                ->attach(Status::class)
                ->default(Status::OPEN),

            Boolean::make(__('Suspend'), 'suspend')
                ->default(false),
        ];
    }

    protected function roles(): array
    {
        return [
            AttachMany::make(__('Role'), 'roles', Role::class),
            BelongsToMany::make(__('Role'), 'roles', Role::class),
        ];
    }

    protected function suspend(): array
    {
        return [
            Heading::make('Status')
                ->hideWhenUpdating($this->isCurrentUserTypeSameAsResoureUserType()),
            Boolean::make(__('Suspend'), 'suspend')
                ->default(false)
                ->hideWhenUpdating($this->isCurrentUserTypeSameAsResoureUserType())
        ];
    }

    protected function isCurrentUserTypeSameAsResoureUserType(): bool
    {
        return method_exists($this, 'getUserType') && user()->type->is($this->getUserType());
    }

    protected function af88GameConditionActionButton(bool $condition): ActionButton
    {
        $action = AF88GameConditionNovaAction::make($this->model());

        return ActionButton::make('AF88 Game Condition')
            ->text('AF88')
            ->action($action, $this->id)
            ->onlyOnIndex()
            ->showOnIndex(fn($request) => $condition)
            ->readonly(fn() => count($action->fields()) <= 0);
    }
}
