<?php

namespace App\Kravanh\Domain\Baccarat\Actions;

use App\Kravanh\Domain\Game\Actions\GameBaccaratGetTableConditionAction;
use App\Kravanh\Domain\Game\Actions\GameTableConditionUpdateOrCreateAction;
use App\Kravanh\Domain\Game\Dto\GameTableConditionData;
use App\Kravanh\Domain\Game\Models\GameTableCondition;
use App\Kravanh\Domain\User\Models\Member;
use App\Kravanh\Domain\User\Supports\Enums\UserType;
use App\Kravanh\Support\Enums\Currency;
use App\Models\User;

final class BaccaratGameAutoSetDefaultBetConditionAction
{
    public function __invoke(User|Member $member): void
    {
        if (! $member->isMember()) {
            return;
        }

        if ($this->canPlay($member->id, $member->getGameTableId())) {
            return;
        }

        if (! $this->isSuperSeniorAllow($member->super_senior)) {
            return;
        }

        $superSeniorCondition = app(GameBaccaratGetTableConditionAction::class)($member->super_senior, $member->getGameTableId());

        $this->setCondition($superSeniorCondition, $member->senior, UserType::SENIOR);
        $this->setCondition($superSeniorCondition, $member->master_agent, UserType::MASTER_AGENT);
        $this->setCondition($superSeniorCondition, $member->agent, UserType::AGENT);

        $this->setConditionForMember($superSeniorCondition, $member);

    }

    private function setCondition(GameTableConditionData $condition, int $userId, string $userType): void
    {
        app(GameTableConditionUpdateOrCreateAction::class)(
            data: $this->rebuildCondition($condition, $userId, $userType)
        );

    }

    private function setConditionForMember(GameTableConditionData $superSeniorCondition, User|Member $member): void
    {
        $memberCondition = $this->rebuildCondition($superSeniorCondition, $member->id, UserType::MEMBER);
        $memberCondition->betCondition = $this->defaultConditionForMember($member->currency->value);

        app(GameTableConditionUpdateOrCreateAction::class)(
            data: $memberCondition
        );

    }

    private function rebuildCondition(GameTableConditionData $condition, int $userId, string $userType): GameTableConditionData
    {
        $condition->userId = $userId;
        $condition->userType = $userType;
        $condition->shareAndCommission['share'] = 0;
        $condition->shareAndCommission['commission'] = 0;
        $condition->shareAndCommission['upline_share'] = 0;

        return $condition;
    }

    private function defaultConditionForMember(string $currency): array
    {
        [$min, $max] = $this->getMinMax($currency);

        return [
            GameTableConditionData::MATCH_LIMIT => $this->getGameLimitAndWinLimit($currency)[0],
            GameTableConditionData::WIN_LIMIT_PER_DAY => $this->getGameLimitAndWinLimit($currency)[1],
            GameTableConditionData::DRAGON_TIGER_MIN_BET_PER_TICKET => $min,
            GameTableConditionData::DRAGON_TIGER_MAX_BET_PER_TICKET => $max,
            GameTableConditionData::TIE_MIN_BET_PER_TICKET => $min,
            GameTableConditionData::TIE_MAX_BET_PER_TICKET => (int) floor($max / 7),
            GameTableConditionData::RED_BLACK_MIN_BET_PER_TICKET => $min,
            GameTableConditionData::RED_BLACK_MAX_BET_PER_TICKET => $max,
        ];

    }

    private function getMinMax(string $currency): array
    {
        return match ($currency) {
            Currency::KHR => [4000, 80000],
            Currency::USD => [1, 20],
            Currency::THB => [30, 600],
            Currency::VND => [22000, 4400000]
        };
    }

    private function getGameLimitAndWinLimit(string $currency): array
    {
        //game limit, win limit
        return match ($currency) {
            Currency::KHR => [80000, 800000],
            Currency::USD => [20, 200],
            Currency::THB => [300, 9000],
            Currency::VND => [220000, 5400000]
        };
    }

    private function isSuperSeniorAllow(int $superSeniorId): bool
    {
        return (bool) GameTableCondition::query()
            ->where('user_id', $superSeniorId)
            ->where('is_allowed', 1)
            ->count();
    }

    private function canPlay(int $userId, int $gameTableId): bool
    {
        return (bool) GameTableCondition::query()
            ->where('user_id', $userId)
            ->where('game_table_id', $gameTableId)
            ->count();
    }
}
