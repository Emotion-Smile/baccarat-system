<?php

namespace App\Kravanh\Domain\Baccarat\Actions;

use App\Kravanh\Domain\Baccarat\Dto\BaccaratGameMemberBetData;
use App\Kravanh\Domain\Baccarat\Exceptions\BaccaratGameBetConditionException;
use App\Kravanh\Domain\Game\Actions\GameBaccaratGetTableConditionAction;
use App\Kravanh\Domain\Game\Dto\GameTableConditionData;

final class BaccaratGameBetValidatorAction
{
    public GameTableConditionData $condition;

    public function __construct(public BaccaratGameMemberBetData $memberBet)
    {

        $this->condition = app(GameBaccaratGetTableConditionAction::class)(
            userId: $this->memberBet->member->id,
            tableId: $this->memberBet->member->getGameTableId()
        );

    }

    public static function make(BaccaratGameMemberBetData $betData): BaccaratGameBetValidatorAction
    {
        return new BaccaratGameBetValidatorAction($betData);
    }

    /**
     * @throws BaccaratGameBetConditionException
     */
    public static function validate(BaccaratGameMemberBetData $betData): void
    {
        BaccaratGameBetValidatorAction::make($betData)->validateCondition();
    }

    /**
     * @throws BaccaratGameBetConditionException
     */
    public function validateCondition(): void
    {
        if (! $this->memberBet->isAccountEnabled()) {
            throw BaccaratGameBetConditionException::accountNotAllow();
        }

        if (! $this->condition->isAllowed) {
            throw BaccaratGameBetConditionException::accountNotAllow();
        }

        if (! $this->memberBet->game->canBet()) {
            throw BaccaratGameBetConditionException::bettingClosed();
        }

        if ($this->memberBet->amount < $this->getMinPerTicket()) {
            throw BaccaratGameBetConditionException::invalidMinPerTicket();
        }

        if ($this->memberBet->amount > $this->getMaxPerTicket()) {
            throw BaccaratGameBetConditionException::invalidMaxPerTicket();
        }

        if ($this->memberTotalBetKHRAmountOnThisGame() + $this->fromMemberCurrencyToKHR($this->memberBet->amount) > $this->fromMemberCurrencyToKHR($this->condition->getGameLimit())) {
            throw BaccaratGameBetConditionException::invalidMaxPerTicket();
        }

        if ($this->memberTodayWinLoseKHRAmount() > $this->fromMemberCurrencyToKHR($this->condition->getWinLimitPerDay())) {
            throw BaccaratGameBetConditionException::overWinLimitPerDay();
        }

    }

    public function memberTodayWinLoseKHRAmount(): int
    {
        return (new BaccaratGameGetMemberWinLoseAmountTodayAction())(
            userId: $this->memberBet->member->id
        );
    }

    public function memberTotalBetKHRAmountOnThisGame(): int
    {
        return (new BaccaratGameGetMemberTotalBetAmountAction())(
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
