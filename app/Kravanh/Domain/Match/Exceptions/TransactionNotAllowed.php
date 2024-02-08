<?php

namespace App\Kravanh\Domain\Match\Exceptions;

use App\Kravanh\Support\DomainException;

class TransactionNotAllowed extends DomainException
{
    public static function disableMemberBet(): TransactionNotAllowed
    {
        return new self('transaction.block_transaction');
    }

    public static function disableWithdrawDeposit(): TransactionNotAllowed
    {
        return new self('transaction.block_transaction');
    }
}
