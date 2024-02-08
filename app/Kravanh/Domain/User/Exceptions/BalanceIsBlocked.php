<?php

namespace App\Kravanh\Domain\User\Exceptions;

use App\Kravanh\Support\DomainException;

class BalanceIsBlocked extends DomainException
{
    protected $message = 'balance.balance_is_blocked';
}
