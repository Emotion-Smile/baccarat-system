<?php

namespace App\Kravanh\Domain\Match\Exceptions;

use App\Kravanh\Support\DomainException;

class BetAmountHigherThanMaximumPricePerTicketAllowed extends DomainException
{
    protected $message = 'betting.bet_amount_higher_than_maximum_price_per_ticket_allowed';
}
