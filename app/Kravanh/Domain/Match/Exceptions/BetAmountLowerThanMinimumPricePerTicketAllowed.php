<?php

namespace App\Kravanh\Domain\Match\Exceptions;

use App\Kravanh\Support\DomainException;

class BetAmountLowerThanMinimumPricePerTicketAllowed extends DomainException
{
    protected $message = 'betting.bet_amount_lower_than_minimum_price_per_ticket_allowed';
}
