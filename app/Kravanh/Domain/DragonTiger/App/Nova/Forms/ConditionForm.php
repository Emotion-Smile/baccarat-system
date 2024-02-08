<?php

namespace App\Kravanh\Domain\DragonTiger\App\Nova\Forms;

use App\Kravanh\Domain\Game\Actions\GameDragonTigerGetCompanyTableConditionAction;
use App\Kravanh\Domain\Game\Actions\GameDragonTigerGetParentTableConditionAction;
use App\Kravanh\Domain\Game\Actions\GameDragonTigerGetTableConditionAction;
use App\Kravanh\Domain\Game\Dto\GameTableConditionData;
use App\Models\User;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Heading;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;

final class ConditionForm
{

    public GameTableConditionData $condition;
    public GameTableConditionData $conditionParent;

    public int $userId;
    public string $currency;

    protected bool $ignoreThreshold = false;

    public function __construct(public User $user)
    {
        $this->userId = $this->user->id ?? 0;
        $this->currency = $this->user->currency ?? '';

        $this->condition = $this->getTableCondition();

        $this->conditionParent = $this->isUserUseParentCondition()
            ? $this->condition
            : $this->getParentTableCondition();


        //TODO clean it later
        if ($this->isSuperSenior()) {
            $this->ignoreValidationLessOrGreaterThan();
        }

    }

    public static function make(User $user): ConditionForm
    {
        return (new ConditionForm($user));
    }

    public function buildFields(): array
    {
        return [
            Hidden::make('user_id')->default($this->userId),
            ...$this->allowField(),
            ...$this->shareCommissionField(),
            ...$this->gameWinLimitField(),
            ...$this->dragonTigerField(),
            ...$this->redBlackField()
        ];
    }

    public function ignoreValidationLessOrGreaterThan(): ConditionForm
    {
        $this->ignoreThreshold = true;
        return $this;
    }

    public function getTableCondition()
    {
        $condition = app(GameDragonTigerGetTableConditionAction::class)(userId: $this->userId);

        if (!$condition->isDefault()) {
            return $condition;
        }

        return $this->getParentTableCondition();

    }

    public function getParentTableCondition()
    {
        return $this->isSuperSenior()
            ? app(GameDragonTigerGetCompanyTableConditionAction::class)()
            : app(GameDragonTigerGetParentTableConditionAction::class)(userId: $this->userId);
    }

    public function isUserUseParentCondition(): bool
    {
        return $this->condition->userId !== $this->userId;
    }

    public function allowField(): array
    {

        if ($this->isSuperSenior()) {
            return [
                Boolean::make('Allow', GameTableConditionData::IS_ALLOWED)
                    ->default($this->condition->isAllowed),
            ];
        }

        return [
            Hidden::make('Allow', GameTableConditionData::IS_ALLOWED)
                ->default($this->condition->isAllowed)
        ];

    }

    public function shareCommissionField(): array
    {
        if ($this->user->isMember()) {
            return [
                Hidden::make(GameTableConditionData::ORIGINAL_UPLINE_SHARE)
                    ->default($this->conditionParent->getShare()),
                Hidden::make(GameTableConditionData::UP_LINE_SHARE)
                    ->default($this->isUserUseParentCondition() ? 0 : $this->condition->getUplineShare()),
                Hidden::make(GameTableConditionData::SHARE)->default(0),
                Hidden::make(GameTableConditionData::COMMISSION)->default(0)
            ];
        }

        return [
            Heading::make('Share & Commission (%)'),

            Hidden::make(GameTableConditionData::ORIGINAL_UPLINE_SHARE)
                ->default($this->conditionParent->getShare()),

            Hidden::make(GameTableConditionData::UP_LINE_SHARE)
                ->default(
                    $this->isUserUseParentCondition() ? 0 : $this->condition->getUplineShare()),

            $this->makeShareField(),
            $this->makeCommissionField()
                ->readonly($this->condition->isUpdate())


            // on calculate need to divide by 100.
            // Formula: commission percentage / 100 * turn over = commission amount
            // Ex. 0.01 / 100 * 1000 = 0.10$
        ];
    }

    public function gameWinLimitField(): array
    {
        return [
            Heading::make("Game & Win Limit ($this->currency)"),

            $this->makeMaximumNumber(
                name: GameTableConditionData::MATCH_LIMIT,
                label: 'Game Limit'
            ),

            $this->makeMaximumNumber(
                name: GameTableConditionData::WIN_LIMIT_PER_DAY,
                label: 'Win Limit'
            )

        ];
    }

    public function dragonTigerField(): array
    {

        return [
            Heading::make("Dragon & Tiger allow betting per ticket  ($this->currency)"),

            $this->makeMinimumNumber(
                name: GameTableConditionData::DRAGON_TIGER_MIN_BET_PER_TICKET
            ),

            $this->makeMaximumNumber(
                name: GameTableConditionData::DRAGON_TIGER_MAX_BET_PER_TICKET
            )

        ];

    }

    public function redBlackField(): array
    {
        return [
            Heading::make("Red & Black allow betting per ticket ($this->currency)"),

            $this->makeMinimumNumber(
                name: GameTableConditionData::RED_BLACK_MIN_BET_PER_TICKET
            ),

            $this->makeMaximumNumber(
                name: GameTableConditionData::RED_BLACK_MAX_BET_PER_TICKET,
            )

        ];
    }

    private function makeMaximumNumber(
        string $name,
        string $label = 'Maximum',
    ): Number
    {
        return $this->makeNumber(
            name: $name,
            label: $label,
            default: $this->condition->getByField($name),
            rule: $this->makeValueLessThanRole($name)
        );

    }

    private function makeMinimumNumber(
        string $name,
        string $label = 'Minimum'
    ): Number
    {
        return $this->makeNumber(
            name: $name,
            label: $label,
            default: $this->condition->getByField($name),
            rule: $this->makeValueGreaterThanRole($name)
        );
    }

    private function makeNumber(
        string    $name,
        string    $label,
        int|float $default,
        array     $rule
    ): Number
    {
        return Number::make(
            $label,
            $name
        )
            ->default($default)
            ->min(0)
            ->rules($rule);
    }

    private function makeValueLessThanRole(string $field): array
    {
        return $this->makeRole(field: $field, isLessThen: true);
    }

    private function makeValueGreaterThanRole(string $field): array
    {
        return $this->makeRole($field);
    }


    private function makeRole(
        string   $field,
        bool     $isLessThen = false,
        int|null $threshold = null
    ): array
    {
        $rule = ['required', 'numeric'];


        if ($this->ignoreThreshold) {
            return $rule;
        }

        $value = $threshold ?? $this->conditionParent->getByField($field);
        $valueRule = $isLessThen ? ["lte:$value"] : ["gte:$value"];

        return [
            ...$rule,
            ...$valueRule
        ];


    }

    public function isSuperSenior(): bool
    {
        return $this->user->isSuperSenior();
//        return $this->user instanceof SuperSenior;
    }

    protected function makeShareField(): Number
    {
        return $this->makeNumber(
            name: GameTableConditionData::SHARE,
            label: 'Share',
            default: $this->isUserUseParentCondition() ? 0 : $this->condition->getByField(GameTableConditionData::SHARE),
            rule: $this->makeValueLessThanRole(GameTableConditionData::SHARE)
        )
            ->help("Your share: {$this->conditionParent->getShare()}%")
            ->readonly(
                ($this->condition->isUpdate() && !$this->isUserUseParentCondition())
            );
    }

    protected function makeCommissionField(): Text
    {
        $maxCommission = $this->isSuperSenior() ? 100 : $this->conditionParent->getCommission();

        return Text::make(
            'Commission',
            GameTableConditionData::COMMISSION
        )
            ->default(
                $this->isUserUseParentCondition() ? 0 : $this->condition->getCommission()
            )
            ->help("Your commission: {$this->conditionParent->getCommission()}%")
            ->rules(['required', 'numeric', "max:$maxCommission"])
            ->readonly(
                ($this->condition->isUpdate() && !$this->isUserUseParentCondition())
            );

    }

}
