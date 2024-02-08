<?php

namespace App\Kravanh\Domain\Baccarat\Exceptions;

use App\Kravanh\Support\DomainException;

final class BaccaratGameBetConditionException extends DomainException
{
    public static function accountNotAllow(): BaccaratGameBetConditionException
    {
        return new self('betting.account_not_allow');
    }

    public static function invalidMinPerTicket(): BaccaratGameBetConditionException
    {
        return new self('betting.bet_amount_lower_than_minimum_price_per_ticket_allowed');
    }

    public static function invalidMaxPerTicket(): BaccaratGameBetConditionException
    {
        return new self('betting.bet_amount_higher_than_maximum_price_per_ticket_allowed');
    }

    public static function bettingClosed(): BaccaratGameBetConditionException
    {
        return new self('betting.match_betting_closed');
    }

    public static function overWinLimitPerDay(): BaccaratGameBetConditionException
    {
        return new self('betting.over_win_limit_per_day');
    }

}
