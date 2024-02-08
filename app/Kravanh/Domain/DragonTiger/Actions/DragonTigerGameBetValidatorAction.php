<?php

namespace App\Kravanh\Domain\DragonTiger\Actions;

use App\Kravanh\Domain\DragonTiger\Dto\DragonTigerGameMemberBetData;
use App\Kravanh\Domain\DragonTiger\Exceptions\DragonTigerGameBetConditionException;
use App\Kravanh\Domain\Game\Actions\GameDragonTigerGetTableConditionAction;
use App\Kravanh\Domain\Game\Dto\GameTableConditionData;

final class DragonTigerGameBetValidatorAction
{
    public GameTableConditionData $condition;

    public function __construct(public DragonTigerGameMemberBetData $memberBet)
    {

        $this->condition = app(GameDragonTigerGetTableConditionAction::class)(
            userId: $this->memberBet->member->id,
            tableId: $this->memberBet->member->getGameTableId()
        );

    }

    public static function make(DragonTigerGameMemberBetData $betData): DragonTigerGameBetValidatorAction
    {
        return new DragonTigerGameBetValidatorAction($betData);
    }

    /**
     * @throws DragonTigerGameBetConditionException
     */
    public static function validate(DragonTigerGameMemberBetData $betData): void
    {
        DragonTigerGameBetValidatorAction::make($betData)->validateCondition();
    }

    /**
     * @throws DragonTigerGameBetConditionException
     */
    public function validateCondition(): void
    {
        if (! $this->memberBet->isAccountEnabled()) {
            throw DragonTigerGameBetConditionException::accountNotAllow();
        }

        if (! $this->condition->isAllowed) {
            throw DragonTigerGameBetConditionException::accountNotAllow();
        }

        if (! $this->memberBet->game->canBet()) {
            throw DragonTigerGameBetConditionException::bettingClosed();
        }

        if ($this->memberBet->amount < $this->getMinPerTicket()) {
            throw DragonTigerGameBetConditionException::invalidMinPerTicket();
        }

        if ($this->memberBet->amount > $this->getMaxPerTicket()) {
            throw DragonTigerGameBetConditionException::invalidMaxPerTicket();
        }

        if ($this->memberTotalBetKHRAmountOnThisGame() + $this->fromMemberCurrencyToKHR($this->memberBet->amount) > $this->fromMemberCurrencyToKHR($this->condition->getGameLimit())) {
            throw DragonTigerGameBetConditionException::invalidMaxPerTicket();
        }

        if ($this->memberTodayWinLoseKHRAmount() > $this->fromMemberCurrencyToKHR($this->condition->getWinLimitPerDay())) {
            throw DragonTigerGameBetConditionException::overWinLimitPerDay();
        }

    }

    public function memberTodayWinLoseKHRAmount(): int
    {
        return (new DragonTigerGameGetMemberWinLoseAmountTodayAction())(
            userId: $this->memberBet->member->id
        );
    }

    public function memberTotalBetKHRAmountOnThisGame(): int
    {
        return (new DragonTigerGameGetMemberTotalBetAmountAction())(
            gameId: $this->memberBet->game->id,
            userId: $this->memberBet->member->id
        );
    }

    public function getMinPerTicket()
    {
        if ($this->memberBet->bet->isBetOnDragonOrTiger()) {
            return $this->condition->getDragonTigerMinBetPerTicket();
        }

        if ($this->memberBet->bet->isBetOnRedOrBlack()) {
            return $this->condition->getRedBlackMinBetPerTicket();
        }

        return $this->condition->getTieMinBetPerTicket();

    }

    public function getMaxPerTicket()
    {
        if ($this->memberBet->bet->isBetOnDragonOrTiger()) {
            return $this->condition->getDragonTigerMaxBetPerTicket();
        }

        if ($this->memberBet->bet->isBetOnRedOrBlack()) {
            return $this->condition->getRedBlackMaxBetPerTicket();
        }

        return $this->condition->getTieMaxBetPerTicket();
    }

    public function fromMemberCurrencyToKHR(int $amount): float|int
    {
        return $this->memberBet->member->toKHR($amount);
    }
}
